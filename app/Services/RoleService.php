<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\RoleRepositoryInterface;
use App\DTOs\Role\RoleDTO;
use App\Models\Role;
use App\Traits\HasPaginationFormatting;

final readonly class RoleService
{
    use HasPaginationFormatting;

    public function __construct(private RoleRepositoryInterface $repository) {}

    private function toDto(Role $role): RoleDTO
    {
        return RoleDTO::fromModel($role);
    }

    private function dtoToAttributes(RoleDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'slug' => $dto->slug,
            'description' => $dto->description,
        ];
    }

    public function getRole(int $id): Role
    {
        return $this->repository->find($id);
    }

    public function getRoles(): array
    {
        return $this->repository->all()
            ->map(fn (Role $role) => $this->toDto($role))
            ->toArray();
    }

    public function getRolesPaginated(int $perPage = 15): array
    {
        $paginated = $this->repository->paginate($perPage);

        return $this->formatPagination($paginated, fn (Role $role) => $this->toDto($role));
    }

    public function createRole(RoleDTO $dto): RoleDTO
    {
        $role = $this->repository->create($this->dtoToAttributes($dto));

        return $this->toDto($role);
    }

    public function updateRole(Role $role, RoleDTO $dto): RoleDTO
    {
        $updatedRole = $this->repository->update(
            $role,
            $this->dtoToAttributes($dto)
        );

        return $this->toDto($updatedRole);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->repository->delete($role);
    }

    public function getAllRolesWithPermissions(): array
    {
        return $this->repository->paginateWithPermissions()->toArray();
    }
}
