<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_user_can_create_task_with_valid_data(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'Finish report',
                'description' => 'Write the weekly report',
                'notes' => 'Include charts',
                'category_id' => $category->id,
                'priority' => TaskPriority::High->value,
                'status' => TaskStatus::Pending->value,
                'due_date' => now()->addDay()->toDateString(),
                'due_time' => '14:30',
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Finish report',
            'priority' => TaskPriority::High->value,
            'status' => TaskStatus::Pending->value,
        ]);
    }

    public function test_invalid_task_data_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'title' => '',
                'priority' => 'invalid',
                'status' => 'invalid',
            ])
            ->assertRedirect(route('tasks.create'))
            ->assertSessionHasErrors(['title', 'priority', 'status']);
    }

    public function test_user_can_update_and_soft_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Old title',
            'status' => TaskStatus::Pending,
        ]);

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => 'Updated title',
                'description' => 'Updated description',
                'notes' => null,
                'category_id' => null,
                'priority' => TaskPriority::Urgent->value,
                'status' => TaskStatus::InProgress->value,
                'due_date' => null,
                'due_time' => null,
                'reminder_at' => null,
            ])
            ->assertRedirect(route('tasks.show', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'status' => TaskStatus::InProgress->value,
        ]);

        $this->actingAs($user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_cannot_use_another_users_category(): void
    {
        $user = User::factory()->create();
        $otherCategory = Category::factory()->create();

        $this->actingAs($user)
            ->from(route('tasks.create'))
            ->post(route('tasks.store'), [
                'title' => 'Sneaky task',
                'priority' => TaskPriority::Low->value,
                'status' => TaskStatus::Pending->value,
                'category_id' => $otherCategory->id,
            ])
            ->assertRedirect(route('tasks.create'))
            ->assertSessionHasErrors('category_id');
    }

    public function test_cannot_view_another_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('tasks.show', $task))
            ->assertForbidden();

        $this->actingAs($intruder)
            ->get(route('tasks.edit', $task))
            ->assertForbidden();
    }

    public function test_index_paginates_and_scopes_to_owner(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($user)->count(12)->create();
        Task::factory()->for($other)->create(['title' => 'Other user secret task']);

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();
        $response->assertDontSee('Other user secret task');
        $this->assertTrue($response->viewData('tasks')->total() === 12);
        $this->assertTrue($response->viewData('tasks')->count() === 10);
    }

    public function test_enums_cast_correctly_and_completed_sets_timestamp(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'status' => TaskStatus::Pending,
            'completed_at' => null,
        ]);

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => $task->title,
                'description' => $task->description,
                'notes' => $task->notes,
                'category_id' => null,
                'priority' => TaskPriority::Medium->value,
                'status' => TaskStatus::Completed->value,
                'due_date' => null,
                'due_time' => null,
                'reminder_at' => null,
            ])
            ->assertRedirect();

        $task->refresh();

        $this->assertInstanceOf(TaskStatus::class, $task->status);
        $this->assertInstanceOf(TaskPriority::class, $task->priority);
        $this->assertSame(TaskStatus::Completed, $task->status);
        $this->assertNotNull($task->completed_at);
    }
}
