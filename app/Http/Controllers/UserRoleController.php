<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRole\UpdateUserRoleRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserRoleService;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function __construct(private readonly UserRoleService $userRoleService, private readonly RoleService $roleService) {}

    public function edit(User $user)
    {
        return Inertia::render('Management/UserRole/Edit', [
            'user' => $this->userRoleService->getUserWithRoleAssociated($user),
            'roles' => $this->roleService->getRoles(),
        ]);
    }

    public function update(UpdateUserRoleRequest $request, User $user)
    {
        $this->userRoleService->syncRoles($user->id, $request->validated('roles'));

        return redirect()->back()->with('status', 'User updated successfully.');
    }
}
