<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellersTest extends TestCase
{
    use RefreshDatabase;

    protected $resourceUri;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // CREATE
    public function test_can_create_a_seller()
    {

    }

    public function test_create_seller_validation_fails()
    {

    }

    public function test_create_seller_with_duplicate_email_fails()
    {

    }

    // SHOW
    public function test_can_show_a_seller()
    {

    }

    public function test_show_seller_not_found()
    {

    }

    // LIST
    public function test_can_list_all_sellers()
    {

    }

    public function test_can_filter_sellers_by_name()
    {

    }

    // UPDATE
    public function test_can_update_a_seller()
    {

    }

    public function test_update_seller_not_found()
    {

    }

    public function test_update_seller_validation_fails()
    {

    }

    // DELETE
    public function test_can_delete_a_seller()
    {

    }

    public function test_delete_seller_not_found()
    {

    }
}
