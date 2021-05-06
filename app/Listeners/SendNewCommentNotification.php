<?php

namespace App\Listeners;

use App\Events\Commented;
use App\Notifications\NewComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewCommentNotification
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
     * @param  Commented  $event
     * @return void
     */
    public function handle(Commented $event)
    {
    }
}
