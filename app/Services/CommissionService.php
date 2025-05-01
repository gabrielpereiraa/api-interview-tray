<?php

namespace App\Services;

use App\Contracts\CommissionServiceInterface;

class CommissionService implements CommissionServiceInterface
{    
    const TAX_COMMISSION = 8.5;

    public function calculate(float $value): float
    {
      return ($value * self::TAX_COMMISSION) / 100;
    }
}