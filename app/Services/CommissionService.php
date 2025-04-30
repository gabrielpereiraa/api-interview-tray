<?php

namespace App\Services;

class CommissionService
{    
    const TAX_COMMISSION = 8.5;

    public function calculate(float $value): float
    {
      return ($value * self::TAX_COMMISSION) / 100;
    }
}