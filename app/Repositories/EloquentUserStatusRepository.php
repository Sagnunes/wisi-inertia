<?php

namespace App\Repositories;

use App\Contracts\UserStatusRepositoryInterface;
use App\Models\User;

class EloquentUserStatusRepository implements UserStatusRepositoryInterface
{
    public function updateStatus(User $user, int $statusId): bool
    {
        return $user->update(['status_id' => $statusId]);
    }
}
