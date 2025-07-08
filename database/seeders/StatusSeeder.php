<?php

namespace Database\Seeders;

use App\Enums\ObjectType;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     *  a list of status to seed
     */
    private const STATUS_LIST = [
        ['name' => 'Pendente', 'slug' => 'pendente', 'description' => 'À espera de revisão ou aprovação', 'object_type_id' => ObjectType::forAUTHENTICATION->value],
        ['name' => 'Ativo', 'slug' => 'ativo', 'description' => 'Pedido foi aceite', 'object_type_id' => ObjectType::forAUTHENTICATION->value],
        ['name' => 'Suspenso', 'slug' => 'suspenso', 'description' => '', 'object_type_id' => ObjectType::forAUTHENTICATION->value],
        ['name' => 'Não Publicado', 'slug' => 'nao-publicado', 'description' => '', 'object_type_id' => ObjectType::forDigitalCollection->value],
        ['name' => 'Sem associação', 'slug' => 'sem-associacao', 'description' => '', 'object_type_id' => ObjectType::forDigitalCollection->value],
        ['name' => 'Publicado', 'slug' => 'publicado', 'description' => '', 'object_type_id' => ObjectType::forDigitalCollection->value],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(self::STATUS_LIST)->chunk(100)->each(fn ($chuck) => Status::factory()->createMany($chuck));
    }
}
