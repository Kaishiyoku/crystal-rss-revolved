<?php

namespace App\Http\Controllers;

use App\Enums\FeedFilter;
use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|string|null $feedId
     * @param string|null $previousFirstFeedItemChecksum
     * @param string|null $previousLastFeedItemChecksum
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, $feedId = null, $previousFirstFeedItemChecksum = null, $previousLastFeedItemChecksum = null)
    {
        // both checksums must be null or filled
        if ($previousFirstFeedItemChecksum && !$previousLastFeedItemChecksum || !$previousFirstFeedItemChecksum && $previousLastFeedItemChecksum) {
            abort(404);
        }

        // some basic data for the view
        $selectedFeed = $this->getSelectedFeed($feedId);
        $selectedFeedUnreadFeedItemCount = optional($selectedFeed, fn($value) => $value->unreadFeedItems()->count());
        $totalUnreadFeedItems = Auth::user()->feedItems()->unread()->count();
        $feedOptions = $this->getFeedOptions($selectedFeed, $totalUnreadFeedItems);
        $totalUnreadFeedItemCount = $this->getTotalUnreadFeedItemCount($selectedFeed);

        // the previous first and last feed item based on the url param checksums (can be null)
        $previousFirstFeedItem = $this->getPreviousFirstFeedItem($selectedFeed, $previousFirstFeedItemChecksum);
        $previousLastFeedItem = $this->getPreviousLastFeedItem($selectedFeed, $previousLastFeedItemChecksum);

        $newlyFetchedFeedItemCount = $this->getNewlyFetchedFeedItemCount($selectedFeed, $previousFirstFeedItem);

        // the feed items to display
        $unreadFeedItems = $this->getUnreadFeedItems($selectedFeed, $previousFirstFeedItem, $previousLastFeedItem);

        // no more items for the filtered feed, redirect to all feeds dashboard view
        if ($selectedFeed && $totalUnreadFeedItemCount === 0) {
            return redirect()->route('dashboard');
        }

        return view('dashboard', [
            'selectedFeed' => $selectedFeed,
            'selectedFeedUnreadFeedItemCount' => $selectedFeedUnreadFeedItemCount,
            'feedOptions' => $feedOptions,
            'totalUnreadFeedItems' => $totalUnreadFeedItems,
            'newlyFetchedFeedItemCount' => $newlyFetchedFeedItemCount,
            'unreadFeedItems' => $unreadFeedItems,
            'totalUnreadFeedItemCount' => $totalUnreadFeedItemCount,
        ]);
    }

    /**
     * Get the selected feed based on the given feed id delivered by the url param.
     * If empty, null will be returned.
     * The feed id param can be null but should be 'all' if no filter is present.
     *
     * @param int|string|null $feedId
     * @return Feed|null
     */
    private function getSelectedFeed(int|string|null $feedId): ?Feed
    {
        return $feedId === null || $feedId === FeedFilter::All
            ? null
            : Auth::user()->feeds()->findOrFail($feedId);
    }

    /**
     * Get all feed options for the dashboard view's select autocomplete component.
     * Return a collection of items with the following properties:
     *  - label: the feed name
     *  - description: the feed category
     *  - url: the url to the filtered dashboard view
     *
     * @param Feed|null $selectedFeed
     * @param int $totalUnreadFeedItems
     * @return Collection<Feed>
     */
    private function getFeedOptions(?Feed $selectedFeed, int $totalUnreadFeedItems): Collection
    {
        return Auth::user()->feeds()
            ->whereHas('unreadFeedItems')
            ->with('category')
            ->withCount('unreadFeedItems')
            ->get()
            ->map(fn(Feed $feed) => [
                'label' => $feed->name,
                'description' => "{$feed->category->getName()} ({$feed->unread_feed_items_count})",
                'url' => route('dashboard', [$feed]),
            ])
            ->when($selectedFeed, fn($feedOptions) => $feedOptions->prepend(null)->prepend([
                'label' => __('All feeds'),
                'description' => __('Display all feeds') . ' (' . $totalUnreadFeedItems . ')',
                'url' => route('dashboard'),
            ]));
    }

    /**
     * Get the previous first feed item based on the checksum url param.
     * If empty, null will be returned.
     *
     * @param Feed|null $selectedFeed
     * @param string|null $previousFirstFeedItemChecksum
     * @return FeedItem|null
     */
    private function getPreviousFirstFeedItem(?Feed $selectedFeed, ?string $previousFirstFeedItemChecksum): ?FeedItem
    {
        return $previousFirstFeedItemChecksum
            ? Auth::user()->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->whereChecksum($previousFirstFeedItemChecksum)
                ->firstOrFail()
            : null;
    }

    /**
     * Get the previous last feed item based on the checksum url param.
     * If empty, null will be returned.
     *
     * @param Feed|null $selectedFeed
     * @param string|null $previousFirstFeedItemChecksum
     * @return FeedItem|null
     */
    private function getPreviousLastFeedItem(?Feed $selectedFeed, ?string $previousLastFeedItemChecksum): ?FeedItem
    {
        return $previousLastFeedItemChecksum
            ? Auth::user()->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->whereChecksum($previousLastFeedItemChecksum)
                ->firstOrFail()
            : null;
    }

    /**
     * Get the number of feed items of the previous select based on the checksum url params.
     *
     * @param Feed|null $selectedFeed
     * @param FeedItem|null $previousFirstFeedItem
     * @param FeedItem|null $previousLastFeedItem
     * @return int
     */
    private function getPreviousFeedItemCount(?Feed $selectedFeed, ?FeedItem $previousFirstFeedItem, ?FeedItem $previousLastFeedItem): int
    {
        return $previousFirstFeedItem && $previousLastFeedItem
            ? Auth::user()
                ->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->unread()
                ->where('posted_at', '<=', $previousFirstFeedItem->posted_at)
                ->where('posted_at', '>=', $previousLastFeedItem->posted_at)
                ->count()
            : 0;
    }

    /**
     * Get the number of newly fetched feed items.
     *
     * @param Feed|null $selectedFeed
     * @param FeedItem|null $previousFirstFeedItem
     * @return int
     */
    private function getNewlyFetchedFeedItemCount(?Feed $selectedFeed, ?FeedItem $previousFirstFeedItem): int
    {
        return $previousFirstFeedItem
            ? Auth::user()->feedItems()
                ->ofFeed(optional($selectedFeed)->id)
                ->unread()
                ->where('posted_at', '>', $previousFirstFeedItem->posted_at)
                ->count()
            : 0;
    }

    /**
     * Get unread feed items based on the checksum url params and the selected feed.
     *
     * @param Feed|null $selectedFeed
     * @param FeedItem|null $previousFirstFeedItem
     * @param FeedItem|null $previousLastFeedItem
     * @return Collection<FeedItem>
     */
    private function getUnreadFeedItems(?Feed $selectedFeed, ?FeedItem $previousFirstFeedItem, ?FeedItem $previousLastFeedItem): Collection
    {
        $previousItemsCount = $this->getPreviousFeedItemCount($selectedFeed, $previousFirstFeedItem, $previousLastFeedItem);

        return Auth::user()
            ->feedItems()
            ->ofFeed(optional($selectedFeed)->id)
            ->unread()
            ->with('feed')
            ->when($previousFirstFeedItem && $previousLastFeedItem, fn($query) => $query->where('posted_at', '<=', $previousFirstFeedItem->posted_at))
            ->orderByDesc('posted_at')
            ->orderByDesc('feed_items.id')
            ->take($previousItemsCount + config('app.feed_items_per_page'))
            ->get();
    }

    /**
     * Get the total number of unread feed items.
     *
     * @param Feed|null $selectedFeed
     * @return int
     */
    private function getTotalUnreadFeedItemCount(?Feed $selectedFeed): int
    {
        return Auth::user()
            ->feedItems()
            ->ofFeed(optional($selectedFeed)->id)
            ->unread()
            ->count();
    }
}
