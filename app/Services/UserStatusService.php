<?php

namespace App\Services;

use App\Contracts\UserStatusRepositoryInterface;

final readonly class UserStatusService
{
    public function __construct(private UserStatusRepositoryInterface $repository, private UserService $userService) {}

    public function updateUserStatus(int $userId, int $statusId): bool
    {
        $user = $this->userService->getUser($userId);

        return $this->repository->updateStatus($user, $statusId);
    }
}
