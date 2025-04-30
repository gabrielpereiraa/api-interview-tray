<?php 

namespace App\Enums;

class UserRoles
{
    const EMPLOYEE_ID = 0;
    const ADMINISTRATOR_ID = 1;

    public static function all(): array
    {
        return [
          self::EMPLOYEE_ID,
          self::ADMINISTRATOR_ID,
        ];
    }
}