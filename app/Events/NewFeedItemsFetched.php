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

    /**
     * @var string
     */
    public $message;

    /**
     * @var int
     */
    private $userId;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param int $numberOfNewFeedItems
     * @return void
     */
    public function __construct($userId, $numberOfNewFeedItems)
    {
        App::setLocale(Cache::get('locale'));

        $this->userId = $userId;
        $this->message = trans_choice('new_feed_items', $numberOfNewFeedItems);
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
