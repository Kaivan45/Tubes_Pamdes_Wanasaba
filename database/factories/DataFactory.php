<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hargaPerMeter = 1000;

        $meteran = fake()->numberBetween(10, 500);
        $userId = fake()->numberBetween(2, 15); // Assuming user IDs start from 2 to 10

        return [
            'user_id' => fake()->numberBetween(2, 15),
            'meteran' => $meteran,
            'harga' => $meteran * $hargaPerMeter,
            'tanggal' => fake()->date(),
            'status' => fake()->randomElement(['Lunas', 'Belum Lunas']),
            'slug' => $userId . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
