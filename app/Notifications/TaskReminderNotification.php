<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskReminderNotification extends Notification
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
            'type' => 'reminder',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'title' => __('Reminder: :title', ['title' => $this->task->title]),
            'message' => __('Your reminder for ":title" is due.', ['title' => $this->task->title]),
        ];
    }
}
