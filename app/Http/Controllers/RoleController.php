<?php

namespace App\Http\Controllers;

use App\DTOs\Role\RoleDTO;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly RoleService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('viewAny', Role::class);

        $roles = $this->service->getAllRolesWithPermissions();

        return Inertia::render('Management/Roles/Index', [
            'roles' => $roles,
            'can' => [
                'create' => auth()->user()->can('create', Role::class),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);
        $dto = RoleDTO::fromRequest($request->validated());

        $this->service->createRole($dto);

        return to_route('roles.index')->with('status', 'Role created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): Response
    {
        return Inertia::render('Management/Roles/Edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);

        $this->service->updateRole($role, RoleDTO::fromRequest($request->validated()));

        return to_route('roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $this->service->deleteRole($role);

        return redirect()->back()->with('status', 'Role deleted successfully.');
    }
}
