<?php

namespace App\Providers;

use App\Contracts\CommissionServiceInterface;
use App\Contracts\EmailServiceInterface;
use App\Services\CommissionService;
use App\Services\DefaultEmailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EmailServiceInterface::class, DefaultEmailService::class);
        $this->app->bind(CommissionServiceInterface::class, CommissionService::class);
    }

    public function boot()
    {
        //
    }
}
