<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bimble>
 */
class BimbleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'started_at' => Carbon::now()->toDateTimeString(),
            'ended_at' => Carbon::now()->toDateTimeString(),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
