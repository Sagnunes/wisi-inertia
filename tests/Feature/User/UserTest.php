<?php

use App\Enums\Role as RoleEnum;
use App\Enums\User as UserEnum;
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

    // Create permissions for users management
    $permissions = [
        Permission::factory()->create([
            'name' => 'View Users',
            'slug' => UserEnum::VIEW,
        ]),
        Permission::factory()->create([
            'name' => 'Delete Users',
            'slug' => UserEnum::DELETE,
        ]),
        Permission::factory()->create([
            'name' => 'Manage Users',
            'slug' => UserEnum::MANAGE,
        ]),
        Permission::factory()->create([
            'name' => 'Assign Roles',
            'slug' => RoleEnum::ASSIGN,
        ]),
    ];

    // Attach permissions to the WATCHER role
    $watcherRole->permissions()->attach($permissions);
});

// UserController Tests
it('allows a user to view the users list', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $users = User::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('users.index'));

    $response->assertStatus(200);
    foreach ($users as $testUser) {
        $response->assertSee($testUser->name);
    }
});

it('allows a user to delete another user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $userToDelete = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('users.destroy', $userToDelete->id));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'User deleted successfully.');

    $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
});

it('prevents a user from deleting themselves', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::DIRECTOR->getName())->first());

    $response = $this
        ->actingAs($user)
        ->delete(route('users.destroy', $user->id));

    $response->assertStatus(403); // Forbidden
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

// UserRoleController Tests
it('allows a user to view the edit role page for a user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $targetUser = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('user.roles.edit', $targetUser->id));

    $response->assertStatus(200);
    $response->assertSee($targetUser->name);
});

it('allows a user to update roles for another user', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $targetUser = User::factory()->create();
    $role = Role::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('user.roles.update', $targetUser->id), [
            'roles' => [$role->id],
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'User updated successfully.');

    $this->assertDatabaseHas('role_user', [
        'user_id' => $targetUser->id,
        'role_id' => $role->id,
    ]);
});

it('validates role ids when updating user roles', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());
    $targetUser = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('user.roles.update', $targetUser->id), [
            'roles' => ['invalid-id'],
        ]);

    $response->assertSessionHasErrors('roles.0');
});
