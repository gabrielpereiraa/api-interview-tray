<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use App\Services\CommissionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    protected array $rules = [
        'user_id'     => 'required|exists:users,id',
        'seller_id'   => 'required|exists:sellers,id',
        'amount'      => 'required|numeric|min:1',
        'made_at'     => 'required|date',
    ];

    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function create(Request $request, Seller $seller)
    {
        try {
            $adm = auth()->user();

            $request->merge([
                'user_id' => $adm->id,
                'seller_id' => $seller->id
            ]);

            $validatedData = $request->validate($this->rules);
            $validatedData['commission'] = $this->commissionService->calculate($validatedData['amount']);

            $createdSale = Sale::create($validatedData);
            return response(['sale' => $createdSale], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $adm = auth()->user(); //temp

            $request->merge([
                'user_id' => $adm->id,
                'seller_id' => $seller->id
            ]);

            $validatedData = $request->validate($this->rules);
            $validatedData['commission'] = $this->commissionService->calculate($validatedData['amount']);

            $sale->update($validatedData);
            return response(['sale' => $sale], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erro interno'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Sale $sale)
    {
        $adm = auth()->user(); //temp
        $sale->deleted_by = $adm->id;

        $sale->save();
        $sale->delete();

        return response()->noContent(Response::HTTP_OK);
    }
}