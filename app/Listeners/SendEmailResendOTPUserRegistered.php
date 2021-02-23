<?php

namespace App\Listeners;

use App\Events\ResendOTPRegisteredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Mail\ResendOTPRegisteredMail;

class SendEmailResendOTPUserRegistered implements ShouldQueue
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
     * @param  ResendOTPRegisteredEvent  $event
     * @return void
     */
    public function handle(ResendOTPRegisteredEvent $event)
    {
        Mail::to($event->user)->send(new ResendOTPRegisteredMail($event->user, $event->otp));
    }
}
