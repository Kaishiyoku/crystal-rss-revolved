<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedItemController extends Controller
{
    /**
     * @param string|null $previousFirstFeedItemChecksum
     * @param string|null $previousLastFeedItemChecksum
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard($previousFirstFeedItemChecksum = null, $previousLastFeedItemChecksum = null)
    {
        // both checksums must be null or filled
        if ($previousFirstFeedItemChecksum && !$previousLastFeedItemChecksum || !$previousFirstFeedItemChecksum && $previousLastFeedItemChecksum) {
            abort(404);
        }

        $totalUnreadFeedItems = Auth::user()->feedItems()->unread()->count();

        $previousFirstFeedItem = $previousFirstFeedItemChecksum ? Auth::user()->feedItems()->whereChecksum($previousFirstFeedItemChecksum)->firstOrFail() : null;
        $previousLastFeedItem = $previousLastFeedItemChecksum ? Auth::user()->feedItems()->whereChecksum($previousLastFeedItemChecksum)->firstOrFail() : null;

        $previousItemsCount = $previousFirstFeedItemChecksum && $previousLastFeedItemChecksum
            ? Auth::user()
                ->feedItems()
                ->unread()
                ->where('posted_at', '<=', $previousFirstFeedItem->posted_at)
                ->where('posted_at', '>=', $previousLastFeedItem->posted_at)
                ->count()
            : 0;

        $newlyFetchedFeedItemCount = $previousFirstFeedItem ? Auth::user()->feedItems()->unread()->where('posted_at', '>', $previousFirstFeedItem->posted_at)->count() : 0;

        $unreadFeedItems = Auth::user()
            ->feedItems()
            ->unread()
            ->with('feed')
            ->when($previousFirstFeedItem && $previousLastFeedItem, fn($query) => $query->where('posted_at', '<=', $previousFirstFeedItem->posted_at))
            ->orderByDesc('posted_at')
            ->orderByDesc('feed_items.id')
            ->take($previousItemsCount + config('app.feed_items_per_page'))
            ->get();

        return view('dashboard', [
            'totalUnreadFeedItems' => $totalUnreadFeedItems,
            'newlyFetchedFeedItemCount' => $newlyFetchedFeedItemCount,
            'unreadFeedItems' => $unreadFeedItems,
        ]);
    }

    public function toggleMarkAsRead(FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->read_at = $feedItem->read_at ? null : now();
        $feedItem->save();

        return response()->json($feedItem->only(['id', 'read_at']));
    }
}
