<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(4),
            'instructor' => $this->faker->name(),
            'price' => $this->faker->randomFloat(2, 29.99, 299.99),
            'duration_hours' => $this->faker->numberBetween(2, 40),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
        ];
    }
}
