<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\SaleRegisterService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    protected $saleRegisterService;

    protected array $rules = [
        'user_id'     => 'required|exists:users,id',
        'seller_id'   => 'required|exists:sellers,id',
        'amount'      => 'required|numeric|min:1',
        'made_at'     => 'required|date',
    ];

    protected array $updateRules = [
        'amount'      => 'required|numeric|min:1',
        'made_at'     => 'required|date',
    ];

    public function __construct(SaleRegisterService $saleRegisterService)
    {
        $this->saleRegisterService = $saleRegisterService;
    }

    public function create(Request $request, Seller $seller)
    {
        try {
            $this->authorize('create', Sale::class);

            $adm = auth()->user();
            $request->merge([
                'user_id' => $adm->id,
                'seller_id' => $seller->id
            ]);

            $validatedData = $request->validate($this->rules);
            $newSale = $this->saleRegisterService->register($adm, $seller, $validatedData);

            return response(['sale' => $newSale], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index()
    {
        $allSale = Sale::all();
        return response(['sales' => $allSale], Response::HTTP_OK);
    }

    public function indexBySeller(Seller $seller)
    {
        $allSales = $seller->sales()->get();
        return response(['sales' => $allSales], Response::HTTP_OK);
    }

    public function indexByUser(User $user)
    {
        $allSales = $user->sales()->get();
        return response(['sales' => $allSales], Response::HTTP_OK);
    }

    public function show(Sale $sale)
    {
        return response(['sale' => $sale], Response::HTTP_OK);
    }

    public function update(Request $request, Seller $seller, Sale $sale)
    {
        try {
            $this->authorize('update', Sale::class);

            $adm = auth()->user();

            $request->merge([
                'user_id' => $adm->id,
                'seller_id' => $seller->id
            ]);

            $validatedData = $request->validate($this->updateRules);
            $updatedSale = $this->saleRegisterService->update($adm, $seller, $sale, $validatedData);

            return response(['sale' => $updatedSale], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            $this->authorize('delete', Sale::class);

            $adm = auth()->user();
            $this->saleRegisterService->delete($adm, $sale);
            
            return response()->noContent(Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}