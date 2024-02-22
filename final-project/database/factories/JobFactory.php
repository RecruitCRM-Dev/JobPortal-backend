<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(Job::$category),
            'experience' => fake()->numberBetween(0,5),
            'location' => fake()->city(),
            'salary'=> fake()->numberBetween(5_000, 150_000),
            'status'=> fake()->randomElement(Job::$status)
        ];
    }
}
