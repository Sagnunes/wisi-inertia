<?php

namespace Database\Seeders;

use App\Models\ObjectType;
use Illuminate\Database\Seeder;

class ObjectTypeSeeder extends Seeder
{
    private const OBJECT_TYPE_LIST = [
        [
            'name' => 'Authentication',
        ],
        [
            'name' => 'Orders',
        ],
        [
            'name' => 'Digital Collection',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(self::OBJECT_TYPE_LIST)->chunk(100)->each(fn ($chunk) => ObjectType::factory()->createMany($chunk));
    }
}
