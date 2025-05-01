<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === UserRoles::ADMINISTRATOR_ID;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRoles::ADMINISTRATOR_ID;
    }

    public function delete(User $user): bool
    {
        return $user->role === UserRoles::ADMINISTRATOR_ID;
    }
}
