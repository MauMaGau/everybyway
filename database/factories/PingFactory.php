<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ping>
 */
class PingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'data' => '{}',
            'lat' => fake()->randomFloat(9,11,11),
            'lon' => fake()->randomFloat(8,11,11),
            'is_home_area' => false,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
