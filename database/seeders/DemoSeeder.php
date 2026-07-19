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
                'locale' => 'ar',
                'email_verified_at' => now(),
            ]
        );

        $defaultCategories->seedFor($user);

        $categories = $user->categories()->get()->keyBy('name');
        $today = Carbon::today();

        $samples = [
            [
                'title' => 'Prepare weekly report',
                'description' => 'Summarize progress and blockers for the team standup.',
                'category' => 'Work',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::InProgress,
                'due_date' => $today->toDateString(),
                'due_time' => '10:00',
            ],
            [
                'title' => 'Review pull requests',
                'description' => 'Check open PRs and leave feedback before noon.',
                'category' => 'Work',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->toDateString(),
                'due_time' => '12:30',
            ],
            [
                'title' => 'Client check-in call',
                'category' => 'Work',
                'priority' => TaskPriority::Urgent,
                'status' => TaskStatus::Pending,
                'due_date' => $today->toDateString(),
                'due_time' => '16:00',
                'reminder_at' => $today->copy()->setTime(15, 30),
            ],
            [
                'title' => 'Study Laravel policies',
                'description' => 'Read authorization docs and try a policy example.',
                'category' => 'Study',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDay()->toDateString(),
                'due_time' => '19:00',
            ],
            [
                'title' => 'Finish API notes',
                'category' => 'Study',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(2)->toDateString(),
                'due_time' => '20:00',
            ],
            [
                'title' => 'Morning run',
                'category' => 'Health',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Completed,
                'due_date' => $today->copy()->subDay()->toDateString(),
                'due_time' => '07:00',
                'completed_at' => now()->subDay(),
            ],
            [
                'title' => 'Gym session',
                'category' => 'Health',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(1)->toDateString(),
                'due_time' => '18:30',
            ],
            [
                'title' => 'Drink 8 glasses of water',
                'category' => 'Health',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::InProgress,
                'due_date' => $today->toDateString(),
                'due_time' => '21:00',
            ],
            [
                'title' => 'Pay electricity bill',
                'category' => 'Finance',
                'priority' => TaskPriority::Urgent,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->subDays(2)->toDateString(),
                'due_time' => '11:00',
            ],
            [
                'title' => 'Transfer savings',
                'category' => 'Finance',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(4)->toDateString(),
                'due_time' => '09:30',
            ],
            [
                'title' => 'Buy groceries',
                'description' => 'Milk, bread, fruit, and coffee beans.',
                'category' => 'Shopping',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(3)->toDateString(),
                'due_time' => '17:00',
            ],
            [
                'title' => 'Order desk lamp',
                'category' => 'Shopping',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(6)->toDateString(),
                'due_time' => '14:00',
            ],
            [
                'title' => 'Call family',
                'category' => 'Personal',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(5)->toDateString(),
                'due_time' => '20:00',
                'reminder_at' => $today->copy()->addDays(4)->setTime(18, 0),
            ],
            [
                'title' => 'Plan weekend outing',
                'category' => 'Personal',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(2)->toDateString(),
                'due_time' => '15:00',
            ],
            [
                'title' => 'Clean workspace',
                'category' => 'Personal',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Completed,
                'due_date' => $today->copy()->subDays(3)->toDateString(),
                'due_time' => '10:00',
                'completed_at' => now()->subDays(3),
            ],
            [
                'title' => 'Team retrospective',
                'category' => 'Work',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(7)->toDateString(),
                'due_time' => '13:00',
            ],
            [
                'title' => 'Read design article',
                'category' => 'Study',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(8)->toDateString(),
                'due_time' => '21:30',
            ],
            [
                'title' => 'Budget review',
                'category' => 'Finance',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::Pending,
                'due_date' => $today->copy()->addDays(10)->toDateString(),
                'due_time' => '11:00',
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
                    'description' => $sample['description'] ?? 'Demo task seeded for MyTasks.',
                    'notes' => 'You can edit or delete this demo task.',
                    'priority' => $sample['priority'],
                    'status' => $sample['status'],
                    'due_date' => $sample['due_date'],
                    'due_time' => $sample['due_time'] ?? '09:00',
                    'reminder_at' => $sample['reminder_at'] ?? null,
                    'completed_at' => $sample['completed_at'] ?? null,
                ]
            );
        }

        $this->command?->info('Demo ready: demo@mytasks.test / password ('.count($samples).' tasks)');
    }
}
