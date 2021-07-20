<?php

namespace App\Http\Livewire;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Livewire\Component;

class FeedList extends Component
{
    /**
     * @var Cursor|null
     */
    private $cursor = null;

    private $nextCursor;

    public function render()
    {
        /*** @var CursorPaginator $unreadFeedItems */
        $unreadFeedItems = auth()->user()->feedItems()->unread()->with('feed')->orderBy('posted_at', 'desc')->orderBy('feed_items.id', 'desc')->cursorPaginate(2, '*', 'cursor', $this->cursor);

        $this->nextCursor = $unreadFeedItems->nextCursor()->encode();

        return view('livewire.feed-list', [
            'unreadFeedItems' => $unreadFeedItems,
        ]);
    }

    public function loadMore()
    {
        $this->cursor = $this->nextCursor;
    }
}
