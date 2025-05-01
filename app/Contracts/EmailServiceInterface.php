<?php

namespace App\Contracts;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;

interface EmailServiceInterface
{
    public function sendWelcomeEmail(User $user): void;

    public function sendWelcomeSellerEmail(User $user, Seller $seller): void;

    public function sendNewSaleEmail(User $user, Seller $seller, Sale $sale): void;

    public function sendUpdatedSaleEmail(User $user, Seller $seller, Sale $oldSale, Sale $sale): void;
}