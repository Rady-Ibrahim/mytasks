<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCompletedNotification;
use App\Notifications\TaskDueTodayNotification;
use App\Notifications\TaskOverdueNotification;
use App\Notifications\TaskReminderNotification;
use App\Services\NotificationDispatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class Phase10NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Carbon::setTestNow(Carbon::parse('2026-07-15 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_reminder_is_saved_on_task(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'Call dentist',
                'priority' => 'medium',
                'status' => TaskStatus::Pending->value,
                'reminder_at' => '2026-07-15T10:00',
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Call dentist',
        ]);

        $task = Task::query()->where('title', 'Call dentist')->first();
        $this->assertNotNull($task->reminder_at);
    }

    public function test_due_today_notification_is_created(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Due today task',
            'due_date' => '2026-07-15',
            'status' => TaskStatus::Pending,
        ]);

        app(NotificationDispatchService::class)->syncFor($user);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => TaskDueTodayNotification::class,
        ]);

        $this->assertTrue(
            $user->notifications()
                ->where('type', TaskDueTodayNotification::class)
                ->where('data->task_id', $task->id)
                ->exists()
        );
    }

    public function test_overdue_notification_is_idempotent(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create([
            'due_date' => '2026-07-01',
            'status' => TaskStatus::Pending,
        ]);

        $service = app(NotificationDispatchService::class);
        $service->syncFor($user);
        $service->syncFor($user);

        $this->assertSame(
            1,
            $user->notifications()->where('type', TaskOverdueNotification::class)->count()
        );
    }

    public function test_user_can_mark_notification_read(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'due_date' => '2026-07-15',
            'status' => TaskStatus::Pending,
        ]);

        $user->notify(new TaskDueTodayNotification($task));
        $notification = $user->notifications()->first();

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_notifications_are_scoped_to_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $task = Task::factory()->for($other)->create([
            'due_date' => '2026-07-15',
            'status' => TaskStatus::Pending,
        ]);

        $other->notify(new TaskDueTodayNotification($task));

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertDontSee('Due today');
    }

    public function test_completing_task_creates_completed_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'status' => TaskStatus::Pending,
        ]);

        $this->actingAs($user)->post(route('tasks.complete', $task));

        Notification::assertSentTo($user, TaskCompletedNotification::class);
    }

    public function test_reminder_notification_dispatches_when_due(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'status' => TaskStatus::Pending,
            'reminder_at' => Carbon::parse('2026-07-15 11:00:00'),
        ]);

        app(NotificationDispatchService::class)->syncFor($user);

        $this->assertTrue(
            $user->notifications()
                ->where('type', TaskReminderNotification::class)
                ->where('data->task_id', $task->id)
                ->exists()
        );
    }
}
