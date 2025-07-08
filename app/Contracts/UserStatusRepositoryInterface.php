<?php

namespace App\Contracts;

use App\Models\User;

interface UserStatusRepositoryInterface
{
    public function updateStatus(User $user, int $statusId): bool;
}
