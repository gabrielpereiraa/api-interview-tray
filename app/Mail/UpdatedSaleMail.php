<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Seller;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdatedSaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Seller $seller;
    public Sale $oldSale;
    public Sale $sale;

    public function __construct(User $user, Seller $seller, Sale $oldSale, Sale $sale)
    {
        $this->user = $user;
        $this->seller = $seller;
        $this->$oldSale = $oldSale;
        $this->$sale = $sale;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Venda atualizada'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.updated-sale',
            with: [
                'user' => $this->user,
                'seller' => $this->seller,
                'oldSale' => $this->oldSale,
                'sale' => $this->sale,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
