<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
        $published = fake()->numberBetween(0, 1);
        $publishedAt = $published == 0 ? NULL : now();

         $userIds = User::pluck('id')->toArray();

        return [
            "authorId" => $userIds[array_rand($userIds)],
            "title" => $title,
            "metaTitle" => NULL,
            "slug" => $slug,
            "sumary" => NULL,
            "published" => $published,
            "createdAt" => now(),
            "updatedAt" => now(),
            "publishedAt" => $publishedAt,
            "content" => fake()->text(200)
        ];
    }
}