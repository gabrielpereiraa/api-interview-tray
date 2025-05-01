<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function delete(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) return false;
        return $user->role === UserRoles::ADMINISTRATOR_ID;
    }
}