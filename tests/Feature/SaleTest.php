<?php

namespace Tests\Feature;

use App\Models\Seller;
use App\Services\CommissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    protected string $resourceUri;
    protected string $baseResourseUri;

    protected Seller $seller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seller = $this->createSeller($this->adm);
        $this->resourceUri = $this->getSellerSalesUri($this->seller);
        $this->baseResourseUri = $this->getSalesUri();
    }

    // CREATE
    public function test_can_create_a_sale()
    {
        $saleData = $this->getDefaultSaleData();

        $commission = (new CommissionService())->calculate($saleData['amount']);
        $commission = number_format($commission, 2);
        
        $response = $this->post($this->resourceUri, $saleData, $this->authHeader);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['sale']);
        $this->assertDatabaseHas('sales', [
            'amount' => $saleData['amount'],
            'made_at' => $saleData['made_at'],
            'commission' => $commission
        ]);

        $this->assertDatabaseCount('sales', 1);
    }

    public function test_create_sale_validation_fails()
    {
        $saleData = $this->getDefaultSaleData();

        $saleData['amount'] = '';

        $response = $this->post($this->resourceUri, $saleData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    public function test_create_seller_validation_fails()
    {
        $saleData = [
            'amount' => '',
            'made_at' => ''
        ];

        $response = $this->post($this->resourceUri, $saleData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // SHOW
    public function test_can_show_a_sale()
    {
        $sale = $this->createSale($this->adm, $this->seller);

        $response = $this->get("$this->baseResourseUri/$sale->id", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['sale']);
        $response->assertJsonFragment([
            'id' => $sale->id,
            'amount' => $sale->amount
        ]);
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'amount' => $sale->amount
        ]);
    }

    public function test_can_not_find_sale()
    {
        $validSale = $this->createSale($this->adm, $this->seller);
        $invalidSaleID = 99;

        $response = $this->delete("$this->baseResourseUri/$invalidSaleID", [], $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // LIST
    public function test_can_list_all_sales_by_seller()
    {
        $countSales = 10;
        $this->createSales($this->adm, $this->seller, $countSales);

        $response = $this->get("$this->resourceUri", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee(['sales']);
        $response->assertJsonCount($countSales, 'sales');
        $this->assertDatabaseCount('sales', $countSales);
    }

    public function it_can_not_find_sales_by_seller()
    {
        $newSeller = $this->createSeller($this->adm);
        $this->createSale($this->adm, $newSeller);

        $response = $this->get("$this->resourceUri", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'sales');
    }

    public function test_can_list_all_sales()
    {
        $seller1 = $this->createSeller($this->adm);
        $seller2 = $this->createSeller($this->adm);

        $sale1 = $this->createSale($this->adm, $seller1);
        $sale2 = $this->createSale($this->adm, $seller1);
        $sale3 = $this->createSale($this->adm, $seller2);

        $this->resourceUri = $this->getSalesUri();
        $response = $this->get("$this->resourceUri", $this->authHeader);
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $sale1->id]);
        $response->assertJsonFragment(['id' => $sale2->id]);
        $response->assertJsonFragment(['id' => $sale3->id]);
    }

    // UPDATE
    public function test_can_update_a_sale()
    {
        $sale = $this->createSale($this->adm, $this->seller);
        $newData = $this->getDefaultSaleData();

        $commission = (new CommissionService())->calculate($newData['amount']);
        $commission = number_format($commission, 2);

        $response = $this->put("$this->resourceUri/$sale->id", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['sale']);
        $this->assertDatabaseHas('sales', [
            'amount' => $newData['amount'],
            'made_at' => $newData['made_at'],
            'commission' => $commission
        ]);
    }

    public function test_update_sale_not_found()
    {
        $validSale = $this->createSale($this->adm, $this->seller);
        $invalidSaleID = 99;
        $newData = $this->getDefaultSaleData();

        $response = $this->put("$this->resourceUri/$invalidSaleID", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // DELETE
    public function test_can_delete_a_sale()
    {
        $sale = $this->createSale($this->adm, $this->seller);

        $response = $this->delete("$this->baseResourseUri/$sale->id", [], $this->authHeader);
        $response->assertStatus(200);
        $this->assertSoftDeleted('sales', [
            'id' => $sale->id,
        ]);
    }

    public function test_delete_sale_not_found()
    {
        $validSale = $this->createSale($this->adm, $this->seller);
        $invalidSaleID = 99;

        $response = $this->delete("$this->baseResourseUri/$invalidSaleID", [], $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
