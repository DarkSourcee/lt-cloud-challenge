<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3, 8));
        
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'content' => '<p>' . implode('</p><p>', fake()->paragraphs(rand(3, 6))) . '</p>',
            'cover_image' => fake()->boolean(70) ? 'https://picsum.photos/800/400?random=' . rand(1, 1000) : null,
            'published_at' => fake()->boolean(80) ? fake()->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}
