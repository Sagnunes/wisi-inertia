<?php

use App\Enums\Role as RoleEnum;
use App\Enums\Permission as PermissionEnum;
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

it('allows a user to view the permissions list', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $permissions = Permission::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('permissions.index'));

    $response->assertStatus(200);
    foreach ($permissions as $permission) {
        $response->assertSee($permission->name);
    }
});

it('allows a user to create a permission', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $permissionData = [
        'name' => 'Test Permission',
        'slug' => 'test-permission',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('permissions.store'), $permissionData);

    $response->assertRedirect(route('permissions.index'));
    $response->assertSessionHas('status', 'Permission created successfully.');

    $this->assertDatabaseHas('permissions', [
        'name' => 'Test Permission',
        'slug' => 'test-permission',
    ]);
});

it('allows a user to update a permission', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $permission = Permission::factory()->create();

    $updatedData = [
        'name' => 'Updated Permission Name',
        'slug' => 'updated-permission-slug',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('permissions.update', $permission->id), $updatedData);

    $response->assertRedirect(route('permissions.edit', $permission->id));

    $this->assertDatabaseHas('permissions', [
        'id' => $permission->id,
        'name' => 'Updated Permission Name',
        'slug' => 'updated-permission-name',
    ]);
});

it('allows a user to delete a permission', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $permission = Permission::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('permissions.destroy', $permission->id));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Permission deleted successfully.');

    $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
});

it('fails validation with empty name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $permissionData = [
        'name' => '',
        'slug' => 'test-permission',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('permissions.store'), $permissionData);

    $response->assertSessionHasErrors('name');
});

it('fails validation with duplicate name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $existingPermission = Permission::factory()->create(['name' => 'Existing Permission']);

    $permissionData = [
        'name' => 'Existing Permission',
        'slug' => 'test-permission',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('permissions.store'), $permissionData);

    $response->assertSessionHasErrors('name');
});
