<?php

namespace App\Http\Controllers;

use App\Constants\CacheKeys;
use App\Models\User;
use App\Services\RedisCacheService;
use App\Services\UserRegisterService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $registrationService;
    protected $cacheService;

    protected array $rules = [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:6',
    ];

    protected array $updateRules = [
        'name' => 'required|string',
        'password' => 'nullable|string|min:6'
    ];

    public function __construct(UserRegisterService $registrationService, RedisCacheService $cacheService)
    {
        $this->registrationService = $registrationService;
        $this->cacheService = $cacheService;
    }

    public function create(Request $request)
    {
        try {
            $this->authorize('create', User::class);

            $validatedData = $request->validate($this->rules);
            $createdUser = $this->registrationService->register($validatedData);

            return response(['user' => $createdUser], Response::HTTP_CREATED);
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
        try {
            $validatedData = $request->validate([
                'email' => 'nullable|email',
            ]);
    
            if (isset($validatedData['email'])) {
                $user = User::where('email', $validatedData['email'])->firstOrFail();
                return response(['user' => $user], Response::HTTP_OK);
            }
            
            $cacheKey = CacheKeys::ALL_USERS;
            $allUsers = $this->cacheService->get($cacheKey);

            if (!$allUsers) {
                $allUsers = User::all();
                $this->cacheService->store($cacheKey, $allUsers, 10);
            }
    
            return response(['users' => $allUsers], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        return response(['user' => $user], Response::HTTP_OK);
    }

    public function update(Request $request, User $user)
    {
        try {
            $this->authorize('update', User::class);

            $validatedData = $request->validate($this->updateRules);
            $user->update($validatedData);

            return response(['user' => $user], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_BAD_REQUEST);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->authorize('delete', $user);
            $adm = auth()->user();
            $user->deleted_by = $adm->id;
            $user->save();
            $user->delete();
            return response()->noContent(Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->noContent(Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
