<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;

final readonly class UserRoleService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function syncRoles(int $userId, array $roleIds): void
    {
        $user = User::where('id', $userId)->first();
        $user->roles()->sync($roleIds);
    }

    public function getUserWithRoleAssociated(User $user): User
    {
        return $this->userRepository->find($user->id)->load('roles');
    }
}
