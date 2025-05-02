<?php

namespace App\Mail;

use App\Mail\BaseMail;
use App\Models\User;
use App\Models\Seller;
use App\Models\Sale;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewSaleMail extends BaseMail
{
    public User $user;
    public Seller $seller;
    public Sale $sale;

    public function __construct(User $user, Seller $seller, Sale $sale)
    {
        $this->user = $user;
        $this->seller = $seller;
        $this->sale = $sale;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nova venda registrada'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-sale',
            with: [
                'user' => $this->user,
                'seller' => $this->seller,
                'sale' => $this->sale,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
