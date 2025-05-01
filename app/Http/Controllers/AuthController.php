<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {   
        try {
            $credentials = $request->only('email', 'password');
            $token = $this->authService->authenticate($credentials);
            $user = auth()->user();

            return response(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
        } catch (JWTException $e) {
            return response()->noContent(Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->noContent(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function me()
    {
        return response()->json(['user' => auth()->user()], Response::HTTP_OK);
    }

    public function logout()
    {
        $user = auth()->user();
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }
}
