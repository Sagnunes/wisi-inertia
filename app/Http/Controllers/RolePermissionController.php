<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePermission\UpdateRolePermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RolePermissionService;
use Inertia\Inertia;

class RolePermissionController extends Controller
{
    public function __construct(private readonly RolePermissionService $service)
    {
    }

    public function edit(Role $role)
    {
        $role = Role::with('permissions')->where('id', $role->id)->first();
        $permissions = Permission::all();

        return Inertia::render('Management/RolePermission/Edit', compact('role', 'permissions'));
    }

    public function update(UpdateRolePermissionRequest $request, Role $role)
    {
        $this->service->syncPermissions($role->id, $request->validated('permissions'));

        return redirect()->back()->with('status', 'Permissions updated successfully.');
    }
}
