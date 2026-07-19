<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class Phase9CalendarTest extends TestCase
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

    public function test_month_view_lists_tasks_in_month(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'July meeting',
            'due_date' => '2026-07-20',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'August trip',
            'due_date' => '2026-08-02',
        ]);

        $this->actingAs($user)
            ->get(route('calendar', ['view' => 'month', 'date' => '2026-07-15']))
            ->assertOk()
            ->assertSee('July meeting')
            ->assertDontSee('August trip');
    }

    public function test_week_and_day_views_scope_correctly(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Day task',
            'due_date' => '2026-07-15',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Next week task',
            'due_date' => '2026-07-22',
        ]);

        $this->actingAs($user)
            ->get(route('calendar', ['view' => 'day', 'date' => '2026-07-15']))
            ->assertOk()
            ->assertSee('Day task')
            ->assertDontSee('Next week task');

        $this->actingAs($user)
            ->get(route('calendar', ['view' => 'week', 'date' => '2026-07-15']))
            ->assertOk()
            ->assertSee('Day task')
            ->assertDontSee('Next week task');
    }

    public function test_navigation_query_params_work(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('calendar', ['view' => 'month', 'date' => '2026-06-01']))
            ->assertOk()
            ->assertSee('June 2026');
    }

    public function test_other_users_tasks_never_appear(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'My calendar task',
            'due_date' => '2026-07-18',
        ]);
        Task::factory()->for($other)->create([
            'title' => 'Hidden calendar task',
            'due_date' => '2026-07-18',
        ]);

        $this->actingAs($user)
            ->get(route('calendar', ['view' => 'month', 'date' => '2026-07-15']))
            ->assertSee('My calendar task')
            ->assertDontSee('Hidden calendar task');
    }
}
