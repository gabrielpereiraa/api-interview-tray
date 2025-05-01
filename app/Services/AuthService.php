<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    private function returnToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
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
        if (!$user) throw new JWTException('Invalid JWT;');
        return true;
    }

    public function generate(User $user)
    {
        $token = JWTAuth::fromUser($user);
        return $this->returnToken($token);
    }

    public function logout(): bool
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }
}