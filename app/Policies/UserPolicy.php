<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    public function before(User $user, $ability): ?true
    {
        if ($user->hasRole(Role::WATCHER->getName())) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission(\App\Enums\User::VIEW);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        return $currentUser->hasPermission(\App\Enums\User::DELETE) && $currentUser->id !== $targetUser->id;
    }

    public function manage(User $user): bool
    {
        return $user->hasPermission(\App\Enums\User::MANAGE);
    }

    public function assign(User $user): bool
    {
        return $user->hasPermission(\App\Enums\Role::ASSIGN);
    }

    public function validate(User $user): bool
    {
        return $user->hasPermission(\App\Enums\User::validate);
    }
}
