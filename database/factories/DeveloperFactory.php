<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skills = ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Node.js', 'Python', 'MySQL', 'PostgreSQL', 'Docker', 'Git', 'AWS', 'TypeScript', 'TailwindCSS', 'Livewire'];
        
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'seniority' => fake()->randomElement(['Jr', 'Pl', 'Sr']),
            'skills' => fake()->randomElements($skills, rand(3, 7)),
        ];
    }
}
