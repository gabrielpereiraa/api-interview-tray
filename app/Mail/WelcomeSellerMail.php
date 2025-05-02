<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeSellerMail extends BaseMail
{
    public User $user;
    public Seller $seller;

    public function __construct(User $user, Seller $seller)
    {
        $this->user = $user;
        $this->seller = $seller;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bem-vindo à plataforma!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-seller',
            with: [
                'user' => $this->user,
                'seller' => $this->seller,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

