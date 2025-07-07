<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * The list of roles to seed.
     */
    private const ROLE_LIST = [
        ['name' => 'Watcher', 'description' => 'É a entidade máxima do sistema, com acesso irrestrito a todas as funcionalidades e informação. ', 'slug' => 'watcher'],
        [
            'name' => 'Director',
            'slug' => 'director',
            'description' => 'Administra e supervisiona áreas estratégicas do sistema, com permissões avançadas mas sem acesso irrestrito.',
        ],
        [
            'name' => 'Collector',
            'slug' => 'collector',
            'description' => 'Responsável pela gestão da coleção digital e pelo acesso a fotografias de determinados fundos.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(self::ROLE_LIST)->chunk(100)->each(fn ($chuck) => Role::factory()->createMany($chuck));
    }
}
