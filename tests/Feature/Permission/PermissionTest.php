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

it('a user without view permissions cant see the list of permissions', function () {
    $user = User::factory()->create();

    Permission::factory()->count(3)->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Permission::VIEW));

    $response = $this
        ->actingAs($user)
        ->get(route('permissions.index'));

    $response->assertStatus(403);
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

it('a user without create permissions cant create a permission', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Permission::CREATE));
    $permissionData = [
        'name' => 'Test Permission',
        'slug' => 'test-permission',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('permissions.store'), $permissionData);

    $response->assertStatus(403);
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

it('a user witout update permission cant update a permission', function () {
    $user = User::factory()->create();
    $permission = Permission::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Permission::UPDATE));

    $updatedData = [
        'name' => 'Updated Permission Name',
        'slug' => 'updated-permission-slug',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('permissions.update', $permission->id), $updatedData);

    $response->assertStatus(403);
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

it('a user without delete permission cant delete a permission', function () {
    $user = User::factory()->create();
    $permission = Permission::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Permission::DELETE));
    $response = $this
        ->actingAs($user)
        ->delete(route('permissions.destroy', $permission->id));
    $response->assertStatus(403);
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
