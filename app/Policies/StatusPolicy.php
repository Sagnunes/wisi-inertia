<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Status;
use App\Models\User;

class StatusPolicy
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
        return $user->hasPermission(\App\Enums\Status::VIEW);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission(\App\Enums\Status::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Status $status): bool
    {
        return $user->hasPermission(\App\Enums\Status::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Status $status): bool
    {
        return $user->hasPermission(\App\Enums\Status::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Status $status): bool
    {
        return false;
    }
}
