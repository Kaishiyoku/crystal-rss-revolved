<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Support\Facades\Auth;

class FeedItemController extends Controller
{
    /**
     * @param string|null $previousFirstFeedItemChecksum
     * @param string|null $previousLastFeedItemChecksum
     * @param int|null $feedId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboard($previousFirstFeedItemChecksum = null, $previousLastFeedItemChecksum = null, $feedId = null)
    {
        // both checksums must be null or filled
        if ($previousFirstFeedItemChecksum && !$previousLastFeedItemChecksum || !$previousFirstFeedItemChecksum && $previousLastFeedItemChecksum) {
            abort(404);
        }

        $selectedFeed = $feedId ? Auth::user()->feeds()->findOrFail($feedId) : null;

        $feedOptions = Auth::user()->feeds()
            ->whereHas('unreadFeedItems')
            ->with('category')
            ->withCount('unreadFeedItems')
            ->get()
            ->map(fn(Feed $feed) => [
                'label' => $feed->name,
                'description' => "{$feed->category->getName()} ({$feed->unread_feed_items_count})",
                'url' => route('dashboard.filter', [$feed]),
            ])
            ->when($selectedFeed, fn($feedOptions) => $feedOptions->prepend([
                'label' => __('All feeds'),
                'description' => __('Display all feeds') . ' (' . Auth::user()->feedItems()->unread()->count() . ')',
                'url' => route('dashboard'),
            ]));

        $totalUnreadFeedItems = Auth::user()->feedItems()->unread()->count();

        $previousFirstFeedItem = $previousFirstFeedItemChecksum ? Auth::user()->feedItems()->ofFeed(optional($selectedFeed)->id)->whereChecksum($previousFirstFeedItemChecksum)->firstOrFail() : null;
        $previousLastFeedItem = $previousLastFeedItemChecksum ? Auth::user()->feedItems()->ofFeed(optional($selectedFeed)->id)->whereChecksum($previousLastFeedItemChecksum)->firstOrFail() : null;

        $previousItemsCount = $previousFirstFeedItemChecksum && $previousLastFeedItemChecksum
            ? Auth::user()
                ->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->unread()
                ->where('posted_at', '<=', $previousFirstFeedItem->posted_at)
                ->where('posted_at', '>=', $previousLastFeedItem->posted_at)
                ->count()
            : 0;

        $newlyFetchedFeedItemCount = $previousFirstFeedItem
            ? Auth::user()->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->unread()
                ->where('posted_at', '>', $previousFirstFeedItem->posted_at)
                ->count()
            : 0;

        $unreadFeedItems = Auth::user()
            ->feedItems()
            ->ofFeed(optional($selectedFeed)->id)
            ->unread()
            ->with('feed')
            ->when($previousFirstFeedItem && $previousLastFeedItem, fn($query) => $query->where('posted_at', '<=', $previousFirstFeedItem->posted_at))
            ->orderByDesc('posted_at')
            ->orderByDesc('feed_items.id')
            ->take($previousItemsCount + config('app.feed_items_per_page'))
            ->get();

        $totalUnreadFeedItemCount = Auth::user()
            ->feedItems()
            ->ofFeed(optional($selectedFeed)->id)
            ->unread()
            ->count();

        // no more items for the filtered feed, redirect to all feeds dashboard view
        if ($selectedFeed && $totalUnreadFeedItemCount === 0) {
            return redirect()->route('dashboard');
        }

        return view('dashboard', [
            'selectedFeed' => $selectedFeed,
            'feedOptions' => $feedOptions,
            'totalUnreadFeedItems' => $totalUnreadFeedItems,
            'newlyFetchedFeedItemCount' => $newlyFetchedFeedItemCount,
            'unreadFeedItems' => $unreadFeedItems,
            'totalUnreadFeedItemCount' => $totalUnreadFeedItemCount,
        ]);
    }

    /**
     * @param int $feedId
     * @param string|null $previousFirstFeedItemChecksum
     * @param string|null $previousLastFeedItemChecksum
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboardFiltered($feedId, $previousFirstFeedItemChecksum = null, $previousLastFeedItemChecksum = null)
    {
        return $this->dashboard($previousFirstFeedItemChecksum, $previousLastFeedItemChecksum, $feedId);
    }

    public function toggleMarkAsRead(FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->read_at = $feedItem->read_at ? null : now();
        $feedItem->save();

        return response()->json($feedItem->only(['id', 'read_at']));
    }
}
