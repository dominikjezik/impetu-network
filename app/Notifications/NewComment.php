<?php

namespace App\Notifications;

use App\Events\Commented;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    private Commented $event;

    /**
     * Create a new notification instance.
     *
     * @param Commented $event
     */
    public function __construct(Commented $event)
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
            'image' => $this->event->user->profile_photo_url,
            'user' => $this->event->user->name,
            'user_id' => $this->event->user->id,
            'subpage' => $this->event->comment->subPage->title
        ];
    }
}
