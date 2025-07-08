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
        $resources = ['perfis', 'estados', 'permissoes', 'utilizadores'];
        $actions = ['criar', 'ver', 'atualizar', 'apagar'];

        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => ucfirst($action).' '.ucfirst($resource),
                    'slug' => $action.'-'.$resource,
                ];
            }
        }

        $specialPermissions = [
            ['name' => 'Atribuir Perfil', 'slug' => 'atribuir-perfil'],
            ['name' => 'Atribuir Permissão', 'slug' => 'atribuir-permissao'],
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
