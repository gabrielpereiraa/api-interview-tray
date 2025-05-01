<?php

namespace Tests;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Helpers\SaleHelper;
use Tests\Helpers\SellerHelper;
use Tests\Helpers\UserHelper;

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
        $this->adm = $this->createAdmUser();

        $token = app(AuthService::class)->generate($this->adm);
        $this->authHeader = ['Authorization' => "$token[token_type] $token[access_token]"];
    }
}
