<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoSeeder extends Seeder
{
    public function run(DefaultCategoryService $defaultCategories): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'demo@mytasks.test'],
            [
                'name' => 'Demo User',
                'password' => 'password',
                'theme' => 'light',
                'email_verified_at' => now(),
            ]
        );

        $defaultCategories->seedFor($user);

        $categories = $user->categories()->get()->keyBy('name');
        $today = Carbon::today();

        $samples = [
            [
                'title' => 'Prepare weekly report',
                'category' => 'Work',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::InProgress,
                'due_date' => $today->toDateString(),
            ],
            [
                'title' => 'Study Laravel policies',
                'category' => 'Study',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDay()->toDateString(),
            ],
            [
                'title' => 'Morning run',
                'category' => 'Health',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Completed,
                'due_date' => $today->copy()->subDay()->toDateString(),
                'completed_at' => now()->subDay(),
            ],
            [
                'title' => 'Pay electricity bill',
                'category' => 'Finance',
                'priority' => TaskPriority::Urgent,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->subDays(2)->toDateString(),
            ],
            [
                'title' => 'Buy groceries',
                'category' => 'Shopping',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(3)->toDateString(),
            ],
            [
                'title' => 'Call family',
                'category' => 'Personal',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(5)->toDateString(),
                'reminder_at' => $today->copy()->addDays(4)->setTime(18, 0),
            ],
        ];

        foreach ($samples as $sample) {
            Task::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $sample['title'],
                ],
                [
                    'category_id' => $categories[$sample['category']]->id ?? null,
                    'description' => 'Demo task seeded for MyTasks.',
                    'notes' => 'You can edit or delete this demo task.',
                    'priority' => $sample['priority'],
                    'status' => $sample['status'],
                    'due_date' => $sample['due_date'],
                    'due_time' => '09:00',
                    'reminder_at' => $sample['reminder_at'] ?? null,
                    'completed_at' => $sample['completed_at'] ?? null,
                ]
            );
        }
    }
}
