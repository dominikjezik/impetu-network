<?php

namespace App\Notifications;

use App\Events\Voted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentVote extends Notification
{
    use Queueable;

    private Voted $event;

    /**
     * Create a new notification instance.
     *
     * @param Voted $event
     */
    public function __construct(Voted $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'comment_id' => $this->event->voteable->id,
            'image' => $this->event->user->profile_photo_url,
            'user' => $this->event->user->name,
            'user_id' => $this->event->user->id,
            'body' => $this->event->voteable->body,
            'upvote' => $this->event->upvote,
        ];
    }
}
