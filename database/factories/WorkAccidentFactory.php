<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkAccident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkAccident>
 */
class WorkAccidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'employee_id' => User::factory(),
            'name' => fake()->name(),
            'age' => fake()->numberBetween(18, 65),
            'department' => fake()->jobTitle(),
            'occurred_on' => fake()->date(),
            'chronology' => fake()->paragraph(),
            'injured_body_part' => fake()->word(),
            'action_taken' => fake()->sentence(),
            'therapy' => fake()->sentence(),
        ];
    }
}
