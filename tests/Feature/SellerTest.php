<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDatabase;

    protected string $resourceUri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceUri = $this->getSellersUri();
    }

    // CREATE
    public function test_can_create_a_seller()
    {
        $sellerData = $this->getDefaultSellerData();

        $response = $this->post($this->resourceUri, $sellerData, $this->authHeader);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['seller']);
        $this->assertDatabaseHas('sellers', [
            'name' => $sellerData['name'],
            'email' => $sellerData['email'],
        ]);
        $this->assertDatabaseCount('sellers', 1);
    }   

    public function test_create_seller_validation_fails()
    {
        $sellerData = [
            'name' => '',
            'email' => ''
        ];

        $response = $this->post($this->resourceUri, $sellerData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_create_seller_with_duplicate_email_fails()
    {
        $sellerData = $this->getDefaultSellerData();
        $sellerAlreadyExists = $this->createSeller($this->adm, $sellerData);

        $response = $this->post($this->resourceUri, $sellerData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // SHOW
    public function test_can_show_a_seller()
    {
        $seller = $this->createSeller($this->adm);

        $response = $this->get("$this->resourceUri/$seller->id", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['seller']);
        $response->assertJsonFragment([
            'id' => $seller->id,
            'name' => $seller->name,
            'email' => $seller->email
        ]);
        $this->assertDatabaseHas('sellers', [
            'name' => $seller->name,
            'email' => $seller->email,
        ]);
    }

    public function test_show_seller_not_found()
    {
        $validSeller = $this->createSeller($this->adm);
        $invalidSellerID = 99;

        $response = $this->get("$this->resourceUri/$invalidSellerID", $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // LIST
    public function test_can_list_all_sellers()
    {
        $countSellers = 10;
        $this->createSellers($this->adm, $countSellers);

        $response = $this->get("$this->resourceUri", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee(['sellers']);
        $response->assertJsonCount($countSellers, 'sellers');
        $this->assertDatabaseCount('sellers', $countSellers);
    }

    public function test_can_filter_sellers_by_email()
    {
        $countSellers = 10;
        $this->createSellers($this->adm, $countSellers);
        $searchSeller = $this->createSeller($this->adm);

        $queryParam = "email=$searchSeller->email";
        $response = $this->get("$this->resourceUri?$queryParam", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['seller']);
        $response->assertJsonFragment([
            'id' => $searchSeller->id,
            'email' => $searchSeller->email
        ]);
        $this->assertDatabaseHas('sellers', [
            'name' => $searchSeller->name,
            'email' => $searchSeller->email,
        ]);
        $this->assertDatabaseCount('sellers', $countSellers + 1);
    }

    // UPDATE
    public function test_can_update_a_seller()
    {
        $seller = $this->createSeller($this->adm);
        $newData = $this->getDefaultSellerData();

        $response = $this->put("$this->resourceUri/$seller->id", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['seller']);
        $response->assertJsonFragment([
            'id' => $seller->id,
            'name' => $newData['name']
        ]);
        $this->assertDatabaseHas('sellers', [
            'id' => $seller->id,
            'name' => $newData['name']
        ]);
    }

    public function test_update_seller_not_found()
    {
        $validSeller = $this->createSeller($this->adm);
        $invalidSellerID = 99;
        $newData = $this->getDefaultSellerData();

        $response = $this->put("$this->resourceUri/$invalidSellerID", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_seller_validation_fails()
    {
        $seller = $this->createSeller($this->adm);
        $newData = [
            'name' => ''
        ];

        $response = $this->put("$this->resourceUri/$seller->id", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // DELETE
    public function test_can_delete_a_seller()
    {
        $seller = $this->createSeller($this->adm);

        $response = $this->delete("$this->resourceUri/$seller->id", [], $this->authHeader);
        $response->assertStatus(200);
        $this->assertSoftDeleted('sellers', [
            'id' => $seller->id,
        ]);
    }

    public function test_delete_seller_not_found()
    {
        $validSeller = $this->createSeller($this->adm);
        $invalidSellerID = 99;

        $response = $this->delete("$this->resourceUri/$invalidSellerID", [], $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
