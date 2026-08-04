<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SparepartFactory extends Factory
{
    public function definition(): array
    {
        $cost = fake()->numberBetween(100, 1000) * 1000;
        return [
            'part_name' => fake()->randomElement(['RAM 8GB', 'SSD 512GB', 'Keyboard', 'Baterai', 'LCD 14 Inch', 'Thermal Paste', 'Kipas']),
            'stock' => fake()->numberBetween(5, 50),
            'min_stock' => fake()->numberBetween(2, 10),
            'cost_price' => $cost,
            'selling_price' => $cost + ($cost * 0.3), // 30% margin
        ];
    }
}
