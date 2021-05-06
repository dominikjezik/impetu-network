<?php

namespace App\Listeners;

use App\Events\Voted;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentVote;
use App\Notifications\NewPostVote;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewVoteNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Voted  $event
     * @return void
     */
    public function handle(Voted $event)
    {
        switch(get_class($event->voteable)) {
            case Post::class:
                $event->voteable->author->notify(new NewPostVote($event));
                break;
            case Comment::class:
                $event->voteable->author->notify(new NewCommentVote($event));
                break;
        }

    }
}
