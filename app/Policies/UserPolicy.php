<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function manage(User $user): bool
    {
        return $user->hasPermission(\App\Enums\User::MANAGE);
    }

    public function assignRole(User $currentUser, User $targetUser): bool
    {
        return $currentUser->hasPermission(\App\Enums\Role::ASSIGN) && $currentUser->id !== $targetUser->id;
    }

    public function delete(User $currentUser, User $targetUser): bool
    {
        return $currentUser->hasPermission(\App\Enums\User::MANAGE) && $currentUser->id !== $targetUser->id;
    }
}
