<?php

namespace App\Events;

use Cache;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class TestNotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $title;

    public string $message;

    private int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $userId)
    {
        App::setLocale(Cache::get('locale'));

        $this->userId = $userId;
        $this->title = __('Test notitifaction');
        $this->message = __('This is a test notification.');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('test-notification.' . $this->userId);
    }
}
