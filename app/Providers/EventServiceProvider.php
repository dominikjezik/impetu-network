<?php

namespace App\Providers;

use App\Events\Commented;
use App\Events\Voted;
use App\Listeners\SendNewCommentNotification;
use App\Listeners\SendNewVoteNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Voted::class => [
            SendNewVoteNotification::class
        ],
        Commented::class => [
            SendNewCommentNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
