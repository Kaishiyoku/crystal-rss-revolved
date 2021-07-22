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

    public function mount()
    {
        $this->feedItemsPerPage = config('app.feed_items_per_page');
        $this->unreadFeedItems = new Collection();

        $this->loadMore();
    }

    public function render()
    {
        return view('livewire.feed-list', [
            'unreadFeedItems' => $this->unreadFeedItems,
            'hasMoreFeedItems' => $this->hasMoreFeedItems,
        ]);
    }

    public function loadMore($readFeedIds = [])
    {
        $newUnreadFeedItems = auth()->user()->feedItems()
            ->unread()
            ->when(count($readFeedIds) > 0, function (Builder $query) use ($readFeedIds) {
                return $query->orWhereIn('feed_items.id', $readFeedIds);
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

        $this->unreadFeedItems = $this->unreadFeedItems->merge($newSlicedUnreadFeedItems);
    }
}
