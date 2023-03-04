<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $feedId = null, string $previousFirstFeedItemChecksum = null, string $previousLastFeedItemChecksum = null)
    {
        // both checksums must be null or filled
        if ($previousFirstFeedItemChecksum && !$previousLastFeedItemChecksum || !$previousFirstFeedItemChecksum && $previousLastFeedItemChecksum) {
            abort(404);
        }

        // some basic data for the view
        $selectedFeed = $feedId ? Auth::user()->feeds()->findOrFail($feedId) : null;
        $totalUnreadFeedItems = Auth::user()->feedItems()->unread()->count();
        $feedOptions = $this->getFeedOptions($selectedFeed, $totalUnreadFeedItems);

        return Inertia::render('Dashboard', [
            'totalUnreadFeedItems' => $totalUnreadFeedItems,
            'feedOptions' => $feedOptions,
        ]);
    }

    /**
     * Get all feed options for the dashboard view's select autocomplete component.
     * Return a collection of items with the following properties:
     *  - label: the feed name
     *  - description: the feed category
     *  - url: the url to the filtered dashboard view
     */
    private function getFeedOptions(?Feed $selectedFeed, int $totalUnreadFeedItems): Collection
    {
        return Auth::user()->feeds()
            ->whereHas('feedItems', function (Builder $query) {
                $query->unread();
            })
            ->with('category')
//            ->withCount('feedItems', function (Builder $query) {
//                $query->unread();
//            })
            ->orderBy('name')
            ->get()
            ->map(fn(Feed $feed) => [
                'label' => $feed->name,
                'description' => "{$feed->category->name} ({$feed->feed_items_count})",
                'url' => route('dashboard', [$feed]),
            ])
            ->when($selectedFeed, fn($feedOptions) => $feedOptions->prepend(null)->prepend([
                'label' => __('All feeds'),
                'description' => __('Display all feeds') . ' (' . $totalUnreadFeedItems . ')',
                'url' => route('dashboard'),
            ]));
    }
}
