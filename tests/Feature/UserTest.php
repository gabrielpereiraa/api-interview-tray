<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    protected $resourceUri;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // CREATE
    public function test_can_create_a_user()
    {

    }

    public function test_create_user_validation_fails()
    {

    }

    public function test_create_user_with_duplicate_email_fails()
    {

    }

    // SHOW
    public function test_can_show_a_user()
    {

    }

    public function test_show_user_not_found()
    {

    }

    // LIST
    public function test_can_list_all_users()
    {

    }

    public function test_can_filter_users_by_email()
    {

    }

    // UPDATE
    public function test_can_update_a_user()
    {

    }

    public function test_update_user_not_found()
    {

    }

    public function test_update_user_validation_fails()
    {

    }

    // DELETE
    public function test_can_delete_a_user()
    {

    }

    public function test_delete_user_not_found()
    {

    }
}