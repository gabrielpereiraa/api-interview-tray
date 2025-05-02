<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct() { }

    private function returnToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL()
        ];
    }

    public function authenticate(array $credentials): ?array
    {
        $token = JWTAuth::attempt($credentials);
        if (!$token) throw new JWTException('Invalid email or password.');
        return $this->returnToken($token);
    }

    public function validateToken(): bool
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            throw new JWTException('Invalid JWT.');
        }

        return true;
    }

    public function generate(User $user): array
    {
        $token = JWTAuth::fromUser($user);
        return $this->returnToken($token);
    }

    public function logout(): bool
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return true;
    }
}
