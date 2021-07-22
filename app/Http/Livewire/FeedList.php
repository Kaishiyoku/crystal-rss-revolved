<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class FeedList extends Component
{
    /**
     * @var int|null
     */
    public $feedItemsPerPage = null;

    public $offset = 0;

    /**
     * @var bool
     */
    public $hasMoreFeedItems;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $unreadFeedItems;

    /**
     * @var int|null
     */
    public $filterFeedId = null;

    public function mount()
    {
        $this->feedItemsPerPage = config('app.feed_items_per_page');

        $this->loadMore([], true);
    }

    public function render()
    {
        $feeds = auth()->user()->feeds()
            ->whereHas('unreadFeedItems')
            ->orderBy('name')
            ->get();

        return view('livewire.feed-list', [
            'feeds' => $feeds,
            'unreadFeedItems' => $this->unreadFeedItems,
            'hasMoreFeedItems' => $this->hasMoreFeedItems,
            'filterFeedId' => $this->filterFeedId,
        ]);
    }

    public function loadMore($readFeedIds = [], $overwriteCollection = false)
    {
        if ($overwriteCollection) {
            $this->offset = 0;
        }

        $newUnreadFeedItems = auth()->user()->feedItems()
            ->unread()
            ->when(count($readFeedIds) > 0, function (Builder $query) use ($readFeedIds) {
                return $query->orWhereIn('feed_items.id', $readFeedIds);
            })
            ->when($this->filterFeedId, function (Builder $query) {
                return $query->where('feed_id', $this->filterFeedId);
            })
            ->with('feed')
            ->orderBy('posted_at', 'desc')
            ->orderBy('feed_items.id', 'desc')
            ->offset($this->offset)
            ->limit($this->feedItemsPerPage + 1)
            ->get();

        $this->offset = $this->offset + $this->feedItemsPerPage;
        $this->hasMoreFeedItems = $newUnreadFeedItems->count() > $this->feedItemsPerPage;

        $newSlicedUnreadFeedItems = $newUnreadFeedItems->slice(0, -1);

        $this->unreadFeedItems = $overwriteCollection ? $newSlicedUnreadFeedItems : $this->unreadFeedItems->merge($newSlicedUnreadFeedItems);
    }

    public function filterByFeed($feedId)
    {
        $this->filterFeedId = $feedId;

        $this->offset = 0;
        $this->loadMore([], true);
    }
}
