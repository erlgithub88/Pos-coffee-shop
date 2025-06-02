<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
            'desc' => fake()->text(),
            'capital_price' => fake()->numberBetween(10000, 100000),
            'selling_price' => fake()->numberBetween(20000, 1000000),
            'qty' => fake()->numberBetween(1, 50),
        ];
    }
}
