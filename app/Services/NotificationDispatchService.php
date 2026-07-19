<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueTodayNotification;
use App\Notifications\TaskOverdueNotification;
use App\Notifications\TaskReminderNotification;
use Illuminate\Support\Carbon;

class NotificationDispatchService
{
    public function syncFor(User $user): void
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $this->dispatchReminders($user, $now);
        $this->dispatchDueToday($user, $today);
        $this->dispatchOverdue($user, $today);
    }

    private function dispatchReminders(User $user, Carbon $now): void
    {
        Task::query()
            ->where('user_id', $user->id)
            ->whereNotNull('reminder_at')
            ->where('reminder_at', '<=', $now)
            ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
            ->each(function (Task $task) use ($user): void {
                if ($this->alreadyNotified($user, TaskReminderNotification::class, $task->id)) {
                    return;
                }

                $user->notify(new TaskReminderNotification($task));
            });
    }

    private function dispatchDueToday(User $user, Carbon $today): void
    {
        Task::query()
            ->where('user_id', $user->id)
            ->whereDate('due_date', $today)
            ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
            ->each(function (Task $task) use ($user): void {
                if ($this->alreadyNotified($user, TaskDueTodayNotification::class, $task->id)) {
                    return;
                }

                $user->notify(new TaskDueTodayNotification($task));
            });
    }

    private function dispatchOverdue(User $user, Carbon $today): void
    {
        Task::query()
            ->where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
            ->each(function (Task $task) use ($user): void {
                if ($this->alreadyNotified($user, TaskOverdueNotification::class, $task->id)) {
                    return;
                }

                $user->notify(new TaskOverdueNotification($task));
            });
    }

    private function alreadyNotified(User $user, string $type, int $taskId): bool
    {
        return $user->notifications()
            ->where('type', $type)
            ->where('data->task_id', $taskId)
            ->exists();
    }
}
