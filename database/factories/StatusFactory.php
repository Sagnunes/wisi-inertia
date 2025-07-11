<?php

namespace Database\Factories;

use App\Models\ObjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug,
            'description' => fake()->sentence(),
            'object_type_id' => ObjectType::factory(),
        ];
    }
}
