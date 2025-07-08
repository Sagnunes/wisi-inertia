<?php

use App\Models\User;
use Database\Seeders\StatusSeeder;

beforeEach(function () {
    \Pest\Laravel\seed([\Database\Seeders\ObjectTypeSeeder::class, StatusSeeder::class]);
});
test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create(['status_id' => \App\Enums\Status::ACTIVE->value]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
