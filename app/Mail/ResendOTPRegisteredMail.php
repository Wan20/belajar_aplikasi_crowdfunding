<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Otp;

class ResendOTPRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

   
    protected $user;
    protected $otp;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('creatorwan@gmail.com')
            ->view('email_resend_otp_registered')
            ->with([
                'name' => $this->user->username,
                'otp' => $this->otp->otp_code,
            ]);
    }
}
