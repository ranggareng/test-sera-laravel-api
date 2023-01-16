<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// Event
use App\Events\UserCreatedEvent;

class SendEmailVerificationListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        \Mail::to($event->user->email)->send(new \App\Mail\Auth\EmailVerification($event->user));
    }
}
