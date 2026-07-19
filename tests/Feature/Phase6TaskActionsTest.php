<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase6TaskActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_complete_task_sets_status_and_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'status' => TaskStatus::Pending,
            'completed_at' => null,
        ]);

        $this->actingAs($user)
            ->post(route('tasks.complete', $task))
            ->assertRedirect();

        $task->refresh();

        $this->assertSame(TaskStatus::Completed, $task->status);
        $this->assertNotNull($task->completed_at);

        $this->actingAs($user)
            ->post(route('tasks.reopen', $task))
            ->assertRedirect();

        $task->refresh();

        $this->assertSame(TaskStatus::Pending, $task->status);
        $this->assertNull($task->completed_at);
    }

    public function test_soft_deleted_task_is_hidden_from_index(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Visible then trashed',
        ]);

        $task->delete();

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertOk()
            ->assertDontSee('Visible then trashed');

        $this->actingAs($user)
            ->get(route('tasks.trash'))
            ->assertOk()
            ->assertSee('Visible then trashed');
    }

    public function test_restore_brings_task_back(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Restorable task',
        ]);

        $task->delete();

        $this->actingAs($user)
            ->post(route('tasks.restore', $task))
            ->assertRedirect(route('tasks.trash'));

        $this->assertNull($task->fresh()->deleted_at);

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertSee('Restorable task');
    }

    public function test_duplicate_creates_new_owned_pending_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Original task',
            'description' => 'Details',
            'priority' => TaskPriority::High,
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('tasks.duplicate', $task));

        $copy = Task::query()
            ->where('user_id', $user->id)
            ->where('title', 'Original task (Copy)')
            ->first();

        $this->assertNotNull($copy);
        $this->assertNotSame($task->id, $copy->id);
        $this->assertSame(TaskStatus::Pending, $copy->status);
        $this->assertNull($copy->completed_at);
        $this->assertSame('Details', $copy->description);

        $response->assertRedirect(route('tasks.show', $copy));
    }

    public function test_actions_are_forbidden_for_other_users(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();
        $trashed = Task::factory()->for($owner)->create();
        $trashed->delete();

        $this->actingAs($intruder)->post(route('tasks.complete', $task))->assertForbidden();
        $this->actingAs($intruder)->post(route('tasks.duplicate', $task))->assertForbidden();
        $this->actingAs($intruder)->post(route('tasks.restore', $trashed))->assertForbidden();
        $this->actingAs($intruder)->delete(route('tasks.force-delete', $trashed))->assertForbidden();
    }

    public function test_force_delete_removes_task_permanently(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Gone forever',
        ]);
        $task->delete();

        $this->actingAs($user)
            ->delete(route('tasks.force-delete', $task))
            ->assertRedirect(route('tasks.trash'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
