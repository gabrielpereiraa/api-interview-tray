<?php

namespace Tests;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Helpers\SaleHelper;
use Tests\Helpers\SellerHelper;
use Tests\Helpers\UserHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use UserHelper;
    use SellerHelper;
    use SaleHelper;

    protected User $adm;
    protected array $authHeader;

    protected function setUp(): void
    {
        parent::setUp();

        if (!app()->environment('testing')) {
            exit("Invalid environment.");
        }

        Mail::fake();
        Cache::flush();

        $this->adm = $this->createAdmUser();

        $token = app(AuthService::class)->generate($this->adm);
        $this->authHeader = ['Authorization' => "$token[token_type] $token[access_token]"];
    }
}
