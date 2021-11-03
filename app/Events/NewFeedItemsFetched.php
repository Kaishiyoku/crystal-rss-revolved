<?php

namespace App\Events;

use Cache;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class NewFeedItemsFetched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $title;

    public string $message;

    private int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $userId, int $numberOfNewFeedItems)
    {
        App::setLocale(Cache::get('locale'));

        $this->userId = $userId;
        $this->title = __('New articles available');
        $this->message = trans_choice('new_feed_items_message', $numberOfNewFeedItems);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('feed-list.' . $this->userId);
    }
}
