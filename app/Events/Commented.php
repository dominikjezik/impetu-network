<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Commented
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commentable;
    public User $user;
    public Comment $comment;

    /**
     * Create a new event instance.
     *
     * @param $commentable
     * @param User $user
     * @param Comment $comment
     */
    public function __construct($commentable, User $user, Comment $comment)
    {
        $this->commentable = $commentable;
        $this->user = $user;
        $this->comment = $comment;
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
