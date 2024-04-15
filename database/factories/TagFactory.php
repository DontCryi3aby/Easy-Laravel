<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(5);
        $slug = Str::replace(' ', "-", Str::lower($title));
        
        return [
            'title' => $title,
            'metaTitle' => NULL,
            'slug' => $slug,
            'content' => fake()->text(100),
        ];
    }
}