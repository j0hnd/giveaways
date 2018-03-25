<?php

namespace App\Events;

use App\RaffleSignup;
use App\RaffleEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AnnounceWinner implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $signup;

    public $entry;

    /**
     * Create a new event instance.
     *
     * @param RaffleSignup $signup
     * @param RaffleEntry $entry
     *
     * @return void
     */
    public function __construct(RaffleSignup $signup, RaffleEntry $entry)
    {
        $this->signup = $signup;

        $this->entry  = $entry;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('winners');
    }
}
