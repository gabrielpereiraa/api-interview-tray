<?php

namespace App\Providers;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use App\Policies\SalePolicy;
use App\Policies\SellerPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Seller::class => SellerPolicy::class,
        Sale::class => SalePolicy::class
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
