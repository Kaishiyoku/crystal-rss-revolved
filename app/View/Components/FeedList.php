<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class FeedList extends Component
{
    /**
     * @var int|null
     */
    public $feedItemsPerPage = null;

    /**
     * @var \Illuminate\Support\Collection|null
     */
    public $feeds = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->feedItemsPerPage = config('app.feed_items_per_page');

        $this->feeds = auth()->user()->feeds()
            ->whereHas('unreadFeedItems')
            ->withCount(['unreadFeedItems' => function (Builder $query) {
                $query->unread();
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.feed-list');
    }
}
