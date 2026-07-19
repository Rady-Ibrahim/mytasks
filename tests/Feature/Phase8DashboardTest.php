<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class Phase8DashboardTest extends TestCase
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

    public function test_dashboard_requires_auth(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_counts_match_seeded_tasks(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->count(2)->create(['status' => TaskStatus::Pending]);
        Task::factory()->for($user)->count(3)->create(['status' => TaskStatus::Completed, 'completed_at' => now()]);
        Task::factory()->for($user)->create([
            'status' => TaskStatus::Pending,
            'due_date' => '2026-07-10',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');

        $this->assertSame(6, $stats['total']);
        $this->assertSame(3, $stats['completed']);
        $this->assertSame(3, $stats['pending']);
        $this->assertSame(1, $stats['overdue']);
    }

    public function test_overdue_excludes_completed(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Still overdue',
            'status' => TaskStatus::Pending,
            'due_date' => '2026-07-01',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Done late',
            'status' => TaskStatus::Completed,
            'due_date' => '2026-07-01',
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertSame(1, $response->viewData('stats')['overdue']);
        $this->assertTrue($response->viewData('overdueTasks')->contains('title', 'Still overdue'));
        $this->assertFalse($response->viewData('overdueTasks')->contains('title', 'Done late'));
    }

    public function test_todays_tasks_only_due_today(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->create([
            'title' => 'Today item',
            'due_date' => '2026-07-15',
        ]);
        Task::factory()->for($user)->create([
            'title' => 'Tomorrow item',
            'due_date' => '2026-07-16',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertSame(1, $response->viewData('stats')['today']);
        $this->assertTrue($response->viewData('todayTasks')->contains('title', 'Today item'));
        $this->assertFalse($response->viewData('todayTasks')->contains('title', 'Tomorrow item'));
    }

    public function test_completion_percentage_calculated_correctly(): void
    {
        $user = User::factory()->create();

        Task::factory()->for($user)->count(1)->create(['status' => TaskStatus::Completed, 'completed_at' => now()]);
        Task::factory()->for($user)->count(3)->create(['status' => TaskStatus::Pending]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertSame(25.0, $response->viewData('stats')['completion_percentage']);
    }

    public function test_only_current_users_data(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($user)->create(['title' => 'Mine only']);
        Task::factory()->for($other)->create(['title' => 'Secret other task']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertSee('Mine only')
            ->assertDontSee('Secret other task');
    }
}
