<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([RoleSeeder::class, PermissionSeeder::class, ObjectTypeSeeder::class, StatusSeeder::class]);

        $watcher = User::factory()->create([
            'name' => 'The Watcher',
            'email' => 'watcher@madeira.gov.pt',
        ]);
        $watcher->roles()->attach(Role::WATCHER->value);
    }
}
