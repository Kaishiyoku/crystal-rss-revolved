<?php

namespace App\Http\Livewire;

use App\Models\FeedItem;
use Livewire\Component;

class FeedList extends Component
{
    /**
     * @var int
     */
    private const LIMIT_INCREASE = 15;

    /**
     * @var bool
     */
    public $limit = 15;

    public function render()
    {
        $unreadFeedItems = auth()->user()->feedItems()
            ->unread()
            ->with('feed')
            ->orderBy('posted_at', 'desc')
            ->orderBy('feed_items.id', 'desc')
            ->limit($this->limit + 1)
            ->get();

        $hasMoreFeedItems = $unreadFeedItems->count() > $this->limit;

        return view('livewire.feed-list', [
            'unreadFeedItems' => $unreadFeedItems,
            'hasMoreFeedItems' => $hasMoreFeedItems,
        ]);
    }

    public function markAsRead(FeedItem $feedItem)
    {
        if ($feedItem->read_at) {
            return;
        }

        $feedItem->read_at = now();

        $feedItem->save();
    }

    public function loadMore()
    {
        $this->limit = $this->limit + static::LIMIT_INCREASE;
    }
}
