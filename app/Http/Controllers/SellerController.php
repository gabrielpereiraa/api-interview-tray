<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    protected array $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:sellers',
        'created_by' => 'required|exists:users,id',
    ];

    public function create(Request $request)
    {
        try {
            $this->authorize('create', Seller::class);

            $adm = auth()->user();
            $request->merge(['created_by' => $adm->id]);
            $validatedData = $request->validate($this->rules);

            $createdSeller = Seller::create($validatedData);
            return response(['seller' => $createdSeller], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index()
    {
        $allSellers = Seller::all();
        return response(['sellers' => $allSellers], Response::HTTP_OK);
    }

    public function indexByUser(User $user)
    {
        $allSellers = $user->sellers()->get();
        return response(['sellers' => $allSellers], Response::HTTP_OK);
    }

    public function show(Seller $seller)
    {
        return response(['seller' => $seller], Response::HTTP_OK);
    }

    public function update(Request $request, Seller $seller)
    {
        try {
            $this->authorize('update', Seller::class);
            
            unset($this->rules['created_by']);
            $validatedData = $request->validate($this->rules);
            $seller->update($validatedData);

            return response(['seller' => $seller], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Seller $seller)
    {
        try {
            $this->authorize('delete', Seller::class);

            $adm = auth()->user();
            $seller->deleted_by = $adm->id;
            $seller->save();
            $seller->delete();
            return response()->noContent(Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
