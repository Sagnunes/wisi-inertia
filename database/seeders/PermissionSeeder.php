<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = ['role', 'status', 'permission', 'user'];
        $actions = ['create', 'view', 'update', 'delete'];

        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => ucfirst($action).' '.ucfirst($resource).'s',
                    'slug' => $action.'-'.$resource.'s',
                ];
            }
        }

        $specialPermissions = [
            ['name' => 'Assign Role', 'slug' => 'assign-role'],
            ['name' => 'Assign Permission', 'slug' => 'assign-permission'],
        ];

        $permissions = array_merge($permissions, $specialPermissions);

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']]
            );
        }
    }
}
