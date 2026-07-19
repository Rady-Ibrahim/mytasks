<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->word().' '.fake()->randomNumber(3),
            'color' => fake()->randomElement([
                '#0d6efd',
                '#198754',
                '#dc3545',
                '#fd7e14',
                '#6f42c1',
                '#20c997',
            ]),
            'icon' => fake()->randomElement([
                'bi-briefcase',
                'bi-book',
                'bi-person',
                'bi-heart-pulse',
                'bi-cart3',
                'bi-wallet2',
                'bi-tag',
            ]),
        ];
    }
}
