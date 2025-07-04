<?php

namespace App\Services;

use App\Models\User;

class UserRoleService
{
    public function syncRoles(int $userId, array $roleIds): void
    {
        $user = User::where('id', $userId)->first();
        $user->roles()->sync($roleIds);
    }
}
