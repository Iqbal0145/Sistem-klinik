<?php

namespace Database\Factories;

use App\Models\HealthCheckup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthCheckup>
 */
class HealthCheckupFactory extends Factory
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
            'checked_on' => fake()->date(),
            'clinic' => fake()->randomElement(['Poli umum', 'Poli perusahaan']),
            'diagnosis' => fake()->sentence(),
            'treatment' => fake()->sentence(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
