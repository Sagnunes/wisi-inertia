<?php

use App\Enums\Role as RoleEnum;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);
beforeEach(function () {
    seed(DatabaseSeeder::class);
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

it('user without view permission cant see the role list', function () {
    $user = User::factory()->create();

    Role::factory()->count(3)->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Role::VIEW));

    $response = $this
        ->actingAs($user)
        ->get(route('roles.index'));

    $response->assertStatus(403);
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

it('a user without create permission cant create a role', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Role::CREATE));

    $roleData = [
        'name' => 'Test Role',
        'description' => 'This is a test role',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('roles.store'), $roleData);

    $response->assertStatus(403);
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

it('a users without update permissions cant update a role', function () {
    $user = User::factory()->create();

    $role = Role::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Role::UPDATE));
    $updatedData = [
        'name' => 'Updated Role Name',
        'description' => 'Updated role description',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('roles.update', $role->id), $updatedData);

    $response->assertStatus(403);
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
it('a user without delete permission cant delete a role', function () {
    $user = User::factory()->create();

    $role = Role::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('roles.destroy', $role->id));

    $response->assertStatus(403);
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

    Role::factory()->create(['name' => 'Existing Role']);

    $roleData = [
        'name' => 'Existing Role',
        'description' => 'This is a test role',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('roles.store'), $roleData);

    $response->assertSessionHasErrors('name');
});
