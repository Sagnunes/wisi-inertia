<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\DTOs\User\UserDTO;
use App\Models\User;
use App\Traits\HasPaginationFormatting;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class UserService
{
    use HasPaginationFormatting;

    public function __construct(private UserRepositoryInterface $repository)
    {
    }

    private function toDto(User $user): UserDTO
    {
        return UserDTO::fromModel($user);
    }

    public function getUser(int $userId): User
    {
        return $this->repository->find($userId);
    }

    public function getUsers(): array
    {
        return $this->repository->all()
            ->map(fn(User $user) => $this->toDto($user))
            ->toArray();
    }

    public function deleteUser($user): bool
    {
        return $this->repository->delete($user);
    }

    public function getUsersWithRolesAssociated(int $perPage = 15): array
    {
        $paginated = $this->repository->paginateWithRoles($perPage);

        $paginated = $paginated->through(function (User $user) {
            return [
                ...$this->toDto($user)->toArray(),
                'can' => [
                    'delete' => auth()->user()?->can('delete', $user) ?? false,
                    'assign' => auth()->user()?->can('assign', \App\Models\Role::class) ?? false,
                ],
            ];
        });

        return $this->formatPagination($paginated, fn($item) => $item);
    }
}
