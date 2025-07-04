<?php

namespace App\Services;

final readonly class RolePermissionService
{
    public function __construct(
        private RoleService $roleService,
    ) {}

    public function syncPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->roleService->getRole($roleId);
        $role->permissions()->sync($permissionIds);
    }
}
