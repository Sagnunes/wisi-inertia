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

    public function __construct(private UserRepositoryInterface $repository) {}

    private function toDto(User $user): UserDTO
    {
        return UserDTO::fromModel($user);
    }

    private function dtoToAttributes(UserDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'email' => $dto->email,
        ];
    }

    public function getUser(int $userId): User
    {
        return $this->repository->find($userId);
    }

    public function getUsers(): array
    {
        return $this->repository->all()
            ->map(fn (User $user) => $this->toDto($user))
            ->toArray();
    }

    public function getUsersPaginated(int $perPage = 15): array
    {
        $paginated = $this->repository->paginate($perPage);

        return $this->formatPagination($paginated, fn (User $user) => $this->toDto($user));
    }

    public function deleteUser($user): bool
    {
        return $this->repository->delete($user);
    }

    public function getUsersWithRolesAssociated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateWithRoles($perPage);
    }
}
