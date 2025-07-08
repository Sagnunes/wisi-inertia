<?php

use App\Enums\Role as RoleEnum;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);
beforeEach(function () {
    seed(DatabaseSeeder::class);
});

it('a user without permissions cant view the status list', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Status::VIEW));

    Status::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('statuses.index'));

    $response->assertStatus(403);

});
it('allows a user to view the status list', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $statuses = Status::factory()->count(3)->create();

    $response = $this
        ->actingAs($user)
        ->get(route('statuses.index'));

    $response->assertStatus(200);

    foreach ($statuses as $status) {
        $response->assertSee($status->name);
    }
});

it('allows a user to create a status', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $statusData = [
        'name' => 'Test Status',
        'slug' => 'test-status',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('statuses.store'), $statusData);

    $response->assertRedirect(route('statuses.index'));
    $response->assertSessionHas('status', 'Status created successfully.');

    $this->assertDatabaseHas('statuses', [
        'name' => 'Test Status',
        'slug' => 'test-status',
    ]);
});

it('a users without create permission cant create a status', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Status::CREATE));

    $statusData = [
        'name' => 'Test Status',
        'slug' => 'test-status',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('statuses.store'), $statusData);

    $response->assertStatus(403);
});

it('allows a user to update a status', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $status = Status::factory()->create();

    $updatedData = [
        'name' => 'Updated Status Name',
        'slug' => 'updated-status-slug',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('statuses.update', $status->id), $updatedData);

    $response->assertRedirect(route('statuses.edit', $status->id));

    $this->assertDatabaseHas('statuses', [
        'id' => $status->id,
        'name' => 'Updated Status Name',
        'slug' => 'updated-status-name',
    ]);
});

it('a user without update permission cant update a status', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Status::UPDATE));

    $status = Status::factory()->create();

    $updatedData = [
        'name' => 'Updated Status Name',
        'slug' => 'updated-status-slug',
    ];

    $response = $this
        ->actingAs($user)
        ->patch(route('statuses.update', $status->id), $updatedData);

    $response->assertStatus(403);
});

it('allows a user to delete a status', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $status = Status::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('statuses.destroy', $status->id));

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Status deleted successfully.');

    $this->assertDatabaseMissing('statuses', ['id' => $status->id]);
});

it('a user without delete permission cant delete a status', function () {
    $user = User::factory()->create();

    $this->assertFalse($user->hasPermission(\App\Enums\Status::DELETE));
    $status = Status::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('statuses.destroy', $status->id));

    $response->assertStatus(403);
});

it('fails validation with empty name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $statusData = [
        'name' => '',
        'slug' => 'test-statuses',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('statuses.store'), $statusData);

    $response->assertSessionHasErrors('name');
});

it('fails validation with duplicate name', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::where('name', RoleEnum::WATCHER->getName())->first());

    $existingPermission = App\Models\Status::factory()->create(['name' => 'Existing Status']);

    $permissionData = [
        'name' => 'Existing Status',
        'slug' => 'test-status',
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('statuses.store'), $permissionData);

    $response->assertSessionHasErrors('name');
});
