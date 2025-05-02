<?php

namespace App\Http\Controllers;

use App\Constants\CacheKeys;
use App\Models\Seller;
use App\Models\User;
use App\Services\RedisCacheService;
use App\Services\SellerRegisterService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    protected $registrationService;
    protected $cacheService;

    protected array $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:sellers',
        'created_by' => 'required|exists:users,id',
    ];

    protected array $updateRules = [
        'name' => 'required|string',
    ];

    public function __construct(SellerRegisterService $registrationService, RedisCacheService $cacheService)
    {
        $this->registrationService = $registrationService;
        $this->cacheService = $cacheService;
    }

    public function create(Request $request)
    {
        try {
            $this->authorize('create', Seller::class);

            $adm = auth()->user();

            $request->merge(['created_by' => $adm->id]);
            $validatedData = $request->validate($this->rules);
            $newSeller = $this->registrationService->register($adm, $validatedData);

            return response(['seller' => $newSeller], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'nullable|email',
        ]);

        if (isset($validatedData['email'])) {
            $seller = Seller::where('email', $validatedData['email'])->firstOrFail();
            return response(['seller' => $seller], Response::HTTP_OK);
        }

        $cacheKey = CacheKeys::ALL_SELLERS;
        $allSellers = $this->cacheService->get($cacheKey);

        if (!$allSellers) {
            $allSellers = Seller::all()->toArray();
            $this->cacheService->store($cacheKey, $allSellers, 10);
        }

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
            
            $validatedData = $request->validate($this->updateRules);
            $seller->update($validatedData);

            return response(['seller' => $seller], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
