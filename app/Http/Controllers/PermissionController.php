<?php

namespace App\Http\Controllers;

use App\DTOs\Permission\PermissionDTO;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function __construct(private readonly PermissionService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {;
        $permissions = $this->service->getPermissionsPaginated(50);
        return Inertia::render('Management/Permissions/Index', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $dto = PermissionDTO::fromRequest($request->validated());

        $this->service->createPermission($dto);

        return to_route('permissions.index')->with('status', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission): Response
    {
        return Inertia::render('Management/Permissions/Edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->service->updatePermission($permission, PermissionDTO::fromRequest($request->validated()));

        return to_route('permissions.edit', $permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->service->deletePermission($permission);

        return redirect()->back()->with('status', 'Permission deleted successfully.');
    }
}
