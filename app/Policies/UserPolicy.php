<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function manage(User $user): bool
    {
        return $user->hasPermission(\App\Enums\User::MANAGE);
    }

    public function assignRole(User $user)
    {
        return $user->hasPermission(\App\Enums\Role::ASSIGN);
    }
}
