<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $loginUrl;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->loginUrl = config('app.url');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-credentials',
            with: [
                'user' => $this->user,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }
}
