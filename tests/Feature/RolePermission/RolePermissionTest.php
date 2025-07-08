<?php

use App\Enums\Role as RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);
beforeEach(function () {
    seed(DatabaseSeeder::class);
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
