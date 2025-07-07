<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PermissionRepositoryInterface;
use App\DTOs\Permission\PermissionDTO;
use App\Models\Permission;
use App\Traits\HasPaginationFormatting;

final readonly class PermissionService
{
    use HasPaginationFormatting;

    public function __construct(private PermissionRepositoryInterface $repository) {}

    private function toDto(Permission $permission): PermissionDTO
    {
        return PermissionDTO::fromModel($permission);
    }

    private function dtoToAttributes(PermissionDTO $dto): array
    {
        return [
            'name' => $dto->name,
            'slug' => $dto->slug,
        ];
    }

    public function getPermission(int $id): PermissionDTO
    {
        return $this->toDto($this->repository->find($id));
    }

    public function getPermissions(): array
    {
        return $this->repository->all()
            ->map(fn (Permission $permission) => $this->toDto($permission))
            ->toArray();
    }

    public function getPermissionsPaginated(int $perPage = 15): array
    {
        $paginated = $this->repository->paginate($perPage);

        $paginated = $paginated->through(function (Permission $permission) {
            return [
                ...$this->toDto($permission)->toArray(), // your DTO logic
                'can' => [
                    'update' => auth()->user()?->can('update', $permission) ?? false,
                    'delete' => auth()->user()?->can('delete', $permission) ?? false,
                ],
            ];
        });

        return $this->formatPagination($paginated, fn ($item) => $item);
    }

    public function createPermission(PermissionDTO $dto): PermissionDTO
    {
        $permission = $this->repository->create($this->dtoToAttributes($dto));

        return $this->toDto($permission);
    }

    public function updatePermission(Permission $permission, PermissionDTO $dto): PermissionDTO
    {
        $updatedPermission = $this->repository->update(
            $permission,
            $this->dtoToAttributes($dto)
        );

        return $this->toDto($updatedPermission);
    }

    public function deletePermission(Permission $permission): bool
    {
        return $this->repository->delete($permission);
    }
}
