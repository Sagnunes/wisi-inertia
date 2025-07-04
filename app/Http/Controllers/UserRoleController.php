<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRole\UpdateUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserRoleService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function __construct(private readonly UserRoleService $service) {}

    public function edit(User $user)
    {
        $roles = Role::all();

        return Inertia::render('Management/UserRole/Edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRoleRequest $request, User $user)
    {
        $currentUser = Auth::user()->id;
        $this->service->syncRoles($user->id, $request->validated('roles'));

        return redirect()->back()->with('status', 'User updated successfully.');
    }
}
