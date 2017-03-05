<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class makeLike implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $username;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $username, $status)
    {
        $this->message = $message;
        $this->username = $username;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['make-like'];
    }
}
