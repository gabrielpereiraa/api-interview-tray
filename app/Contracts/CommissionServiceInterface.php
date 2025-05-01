<?php

namespace App\Contracts;

interface CommissionServiceInterface
{
    public function calculate(float $value): float;
}
