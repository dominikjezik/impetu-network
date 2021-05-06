<?php

namespace App\Events;

use App\Models\User;
use App\Models\Voteable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Voted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $voteable;
    public User $user;
    public bool $upvote;

    /**
     * Create a new event instance.
     *
     * @param $voteable
     * @param User $user
     * @param bool $upvote
     */
    public function __construct($voteable, User $user, bool $upvote)
    {
        $this->voteable = $voteable;
        $this->user = $user;
        $this->upvote = $upvote;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
