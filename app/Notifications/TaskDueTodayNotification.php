<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDueTodayNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'due_today',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'title' => __('Due today: :title', ['title' => $this->task->title]),
            'message' => __('":title" is due today.', ['title' => $this->task->title]),
        ];
    }
}
