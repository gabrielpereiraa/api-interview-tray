<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeMail extends BaseMail
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Bem-vindo ao sistema',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.welcome',
            with: ['name' => $this->user->name]
        );
    }

    public function attachments()
    {
        return [];
    }
}