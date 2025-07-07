<?php

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test setup for all tests in this file
beforeEach(function () {
    // Create the WATCHER role with all permissions
    $watcherRole = Role::factory()->create([
        'name' => RoleEnum::WATCHER->getName(),
        'slug' => strtolower(RoleEnum::WATCHER->getName()),
    ]);

    // Create permissions for permissions management
    $permissions = [
        Permission::factory()->create([
            'name' => 'View Permissions',
            'slug' => PermissionEnum::VIEW,
        ]),
        Permission::factory()->create([
            'name' => 'Create Permissions',
            'slug' => PermissionEnum::CREATE,
        ]),
        Permission::factory()->create([
            'name' => 'Update Permissions',
            'slug' => PermissionEnum::UPDATE,
        ]),
        Permission::factory()->create([
            'name' => 'Delete Permissions',
            'slug' => PermissionEnum::DELETE,
        ]),
        Permission::factory()->create([
            'name' => 'Assign Permissions',
            'slug' => PermissionEnum::ASSIGN,
        ]),
    ];

    // Attach permissions to the WATCHER role
    $watcherRole->permissions()->attach($permissions);
});

it('allows a user to view the edit permissions page for a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();
    $permissions = Permission::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('roles.permissions.edit', $role->id));

    $response->assertStatus(200);
    $response->assertSee($role->name);
    foreach ($permissions as $permission) {
        $response->assertSee($permission->name);
    }
});

it('allows a user to update permissions for a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();
    $permissions = Permission::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('roles.permissions.update', $role->id), [
            'permissions' => $permissions->pluck('id')->toArray(),
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Permissions updated successfully.');

    foreach ($permissions as $permission) {
        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
    }
});

it('validates permission ids when updating role permissions', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('roles.permissions.update', $role->id), [
            'permissions' => ['invalid-id'],
        ]);

    $response->assertSessionHasErrors('permissions.0');
});

it('can remove all permissions from a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();
    $permissions = Permission::factory()->count(3)->create();
    $role->permissions()->attach($permissions);

    $response = $this
        ->actingAs($user)
        ->patch(route('roles.permissions.update', $role->id), [
            'permissions' => [],
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Permissions updated successfully.');

    $this->assertDatabaseMissing('role_permission', [
        'role_id' => $role->id,
    ]);
});
