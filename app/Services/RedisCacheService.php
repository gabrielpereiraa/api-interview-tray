<?php

namespace App\Services;

use App\Contracts\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisCacheService implements CacheInterface
{
    public function store(string $key, mixed $value, int $ttl): void
    {
        $value = is_string($value) ? $value : json_encode($value);

        if ($ttl) {
            Redis::set($key, $value, 'EX', $ttl);
        } else {
            Redis::set($key, $value);
        }
    }

    public function get(string $key): mixed
    {
        $value = Redis::get($key);
        return json_decode($value, true) ?? $value;
    }

    public function forget(string $key): void
    {
        Redis::del($key);
    }
}
