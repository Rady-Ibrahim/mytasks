<?php

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class Phase7SearchFilterTest extends TestCase
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

    public function test_search_by_title_matches(): void
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create(['title' => 'Prepare presentation']);
        Task::factory()->for($user)->create(['title' => 'Buy groceries']);

        $this->actingAs($user)
            ->get(route('tasks.index', ['q' => 'presentation']))
            ->assertOk()
            ->assertSee('Prepare presentation')
            ->assertDontSee('Buy groceries');
    }

    public function test_search_by_description_and_category_name(): void
    {
        $user = User::factory()->create();
        $work = Category::factory()->for($user)->create(['name' => 'Work']);
        $personal = Category::factory()->for($user)->create(['name' => 'Personal']);

        Task::factory()->for($user)->create([
            'title' => 'Alpha',
            'description' => 'Needs quarterly budget review',
            'category_id' => $personal->id,
        ]);

        Task::factory()->for($user)->create([
            'title' => 'Beta',
            'description' => 'Something else',
            'category_id' => $work->id,
        ]);

        $this->actingAs($user)
            ->get(route('tasks.index', ['q' => 'budget']))
            ->assertSee('Alpha')
            ->assertDontSee('Beta');

        $this->actingAs($user)
            ->get(route('tasks.index', ['q' => 'Work']))
            ->assertSee('Beta')
            ->assertDontSee('Alpha');
    }

    public function test_filter_by_status_priority_and_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create();
        $otherCategory = Category::factory()->for($user)->create();

        Task::factory()->for($user)->create([
            'title' => 'Match all',
            'status' => TaskStatus::Pending,
            'priority' => TaskPriority::High,
            'category_id' => $category->id,
        ]);

        Task::factory()->for($user)->create([
            'title' => 'Wrong status',
            'status' => TaskStatus::Completed,
            'priority' => TaskPriority::High,
            'category_id' => $category->id,
        ]);

        Task::factory()->for($user)->create([
            'title' => 'Wrong priority',
            'status' => TaskStatus::Pending,
            'priority' => TaskPriority::Low,
            'category_id' => $category->id,
        ]);

        Task::factory()->for($user)->create([
            'title' => 'Wrong category',
            'status' => TaskStatus::Pending,
            'priority' => TaskPriority::High,
            'category_id' => $otherCategory->id,
        ]);

        $this->actingAs($user)
            ->get(route('tasks.index', [
                'status' => TaskStatus::Pending->value,
                'priority' => TaskPriority::High->value,
                'category_id' => $category->id,
            ]))
            ->assertSee('Match all')
            ->assertDontSee('Wrong status')
            ->assertDontSee('Wrong priority')
            ->assertDontSee('Wrong category');
    }

    public function test_date_presets_scope_correctly(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Due today',
            'due_date' => '2026-07-15',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Due tomorrow',
            'due_date' => '2026-07-16',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Due next month',
            'due_date' => '2026-08-01',
        ]);

        $this->actingAs($user)
            ->get(route('tasks.index', ['due' => 'today']))
            ->assertSee('Due today')
            ->assertDontSee('Due tomorrow')
            ->assertDontSee('Due next month');

        $this->actingAs($user)
            ->get(route('tasks.index', ['due' => 'tomorrow']))
            ->assertSee('Due tomorrow')
            ->assertDontSee('Due today');

        $this->actingAs($user)
            ->get(route('tasks.index', ['due' => 'this_month']))
            ->assertSee('Due today')
            ->assertSee('Due tomorrow')
            ->assertDontSee('Due next month');
    }

    public function test_sort_by_due_date(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Later task',
            'due_date' => '2026-07-20',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Sooner task',
            'due_date' => '2026-07-16',
        ]);

        $response = $this->actingAs($user)
            ->get(route('tasks.index', [
                'sort' => 'due_date',
                'direction' => 'asc',
            ]));

        $response->assertOk();

        $titles = $response->viewData('tasks')->pluck('title')->all();

        $this->assertSame(['Sooner task', 'Later task'], $titles);
    }

    public function test_filters_never_leak_other_users_tasks(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'My shared keyword task',
            'status' => TaskStatus::Pending,
        ]);
        Task::factory()->for($other)->create([
            'title' => 'Other shared keyword task',
            'status' => TaskStatus::Pending,
        ]);

        $this->actingAs($user)
            ->get(route('tasks.index', [
                'q' => 'shared keyword',
                'status' => TaskStatus::Pending->value,
            ]))
            ->assertSee('My shared keyword task')
            ->assertDontSee('Other shared keyword task');
    }
}
