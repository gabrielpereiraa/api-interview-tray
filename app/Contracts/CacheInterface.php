<?php

namespace App\Contracts;

interface CacheInterface
{
    public function store(string $key, mixed $value, int $ttl): void;
    public function get(string $key): mixed;
    public function forget(string $key): void;
}
