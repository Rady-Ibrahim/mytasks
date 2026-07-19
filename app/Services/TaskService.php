<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $user, array $data): Task
    {
        return $user->tasks()->create($this->prepare($data));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($this->prepare($data));

        return $task->refresh();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(array $data): array
    {
        $data['category_id'] = ($data['category_id'] ?? null) ?: null;
        $data['due_date'] = ($data['due_date'] ?? null) ?: null;
        $data['due_time'] = ($data['due_time'] ?? null) ?: null;
        $data['reminder_at'] = ($data['reminder_at'] ?? null) ?: null;
        $data['description'] = $data['description'] ?? null;
        $data['notes'] = $data['notes'] ?? null;

        if (($data['status'] ?? null) === TaskStatus::Completed->value) {
            $data['completed_at'] = now();
        }

        return $data;
    }
}
