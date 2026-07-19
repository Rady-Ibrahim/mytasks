<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'notes' => fake()->optional()->paragraphs(2, true),
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'status' => TaskStatus::Pending,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+2 weeks')?->format('Y-m-d'),
            'due_time' => fake()->optional()->time('H:i'),
            'reminder_at' => null,
            'completed_at' => null,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (): array => [
            'user_id' => $user->id,
        ]);
    }

    public function withCategory(?Category $category = null): static
    {
        return $this->state(function (array $attributes) use ($category): array {
            $category ??= Category::factory()->create([
                'user_id' => $attributes['user_id'],
            ]);

            return [
                'category_id' => $category->id,
                'user_id' => $category->user_id,
            ];
        });
    }

    public function completed(): static
    {
        return $this->state(fn (): array => [
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);
    }
}
