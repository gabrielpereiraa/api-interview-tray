<?php

namespace App\Services;

use App\Contracts\EmailServiceInterface;
use App\Mail\NewSaleMail;
use App\Mail\WelcomeMail;
use App\Mail\WelcomeSellerMail;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class DefaultEmailService implements EmailServiceInterface
{
    public function sendWelcomeEmail(User $user): void
    {   
        Mail::to($user->email)->send(new WelcomeMail($user));
    }

    public function sendWelcomeSellerEmail(User $user, Seller $seller): void
    {
        Mail::to($seller->email)->send(new WelcomeSellerMail($user, $seller));
    }

    public function sendNewSaleEmail(User $user, Seller $seller, Sale $sale): void
    {
        Mail::to($seller->email)->send(new NewSaleMail($user, $seller, $sale));
    }

    public function sendUpdatedSaleEmail(User $user, Seller $seller, Sale $oldSale, Sale $sale): void
    {
        Mail::to($seller->email)->send(new WelcomeMail($user, $seller, $oldSale, $sale));
    }
}
