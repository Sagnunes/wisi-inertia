<?php

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

    // Create permissions for roles
    $permissions = [
        Permission::factory()->create([
            'name' => 'View Roles',
            'slug' => RoleEnum::VIEW,
        ]),
        Permission::factory()->create([
            'name' => 'Create Roles',
            'slug' => RoleEnum::CREATE,
        ]),
        Permission::factory()->create([
            'name' => 'Update Roles',
            'slug' => RoleEnum::UPDATE,
        ]),
        Permission::factory()->create([
            'name' => 'Delete Roles',
            'slug' => RoleEnum::DELETE,
        ]),
        Permission::factory()->create([
            'name' => 'Assign Roles',
            'slug' => RoleEnum::ASSIGN,
        ]),
    ];

    // Attach permissions to the WATCHER role
    $watcherRole->permissions()->attach($permissions);
});

it('allows a user to view the roles list', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $roles = Role::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('roles.index'));

    $response->assertStatus(200);
    foreach ($roles as $role) {
        $response->assertSee($role->name);
    }
});

it('allows a user to create a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $roleData = [
        'name' => 'Test Role',
        'description' => 'This is a test role',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('roles.store'), $roleData);

    $response->assertRedirect(route('roles.index'));
    $response->assertSessionHas('status', 'Role created successfully.');

    $this->assertDatabaseHas('roles', [
        'name' => 'Test Role',
        'description' => 'This is a test role',
        'slug' => 'test-role',
    ]);
});

it('allows a user to update a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();

    $updatedData = [
        'name' => 'Updated Role Name',
        'description' => 'Updated role description',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('roles.update', $role->id), $updatedData);

    $response->assertRedirect(route('roles.edit', $role->id));

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'Updated Role Name',
        'description' => 'Updated role description',
        'slug' => 'updated-role-name',
    ]);
});

it('allows a user to delete a role', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $role = Role::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('roles.destroy', $role->id));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Role deleted successfully.');

    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});

it('fails validation with empty name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $roleData = [
        'name' => '',
        'description' => 'This is a test role',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('roles.store'), $roleData);

    $response->assertSessionHasErrors('name');
});

it('fails validation with duplicate name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $existingRole = Role::factory()->create(['name' => 'Existing Role']);

    $roleData = [
        'name' => 'Existing Role',
        'description' => 'This is a test role',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('roles.store'), $roleData);

    $response->assertSessionHasErrors('name');
});
