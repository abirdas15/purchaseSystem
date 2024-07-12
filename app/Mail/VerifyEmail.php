<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this->subject(env('APP_NAME') . ': Email Verification')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->view('emails.verify-email')->with([
                'user' => $this->user
            ]);
    }

}
