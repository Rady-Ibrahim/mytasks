<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase11FinalQaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_demo_seeder_runs(): void
    {
        $this->seed(DemoSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'demo@mytasks.test',
        ]);

        $user = User::query()->where('email', 'demo@mytasks.test')->firstOrFail();

        $this->assertGreaterThanOrEqual(6, $user->categories()->count());
        $this->assertGreaterThanOrEqual(6, $user->tasks()->count());
    }

    public function test_critical_happy_path(): void
    {
        $this->post(route('register'), [
            'name' => 'Happy Path',
            'email' => 'happy@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'happy@example.com')->firstOrFail();

        $this->actingAs($user)
            ->post(route('tasks.store'), [
                'title' => 'Ship MyTasks',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => now()->toDateString(),
            ])
            ->assertRedirect(route('tasks.index'));

        $task = $user->tasks()->where('title', 'Ship MyTasks')->firstOrFail();

        $this->actingAs($user)
            ->post(route('tasks.complete', $task))
            ->assertRedirect();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Welcome, Happy Path')
            ->assertSee('Ship MyTasks');

        $this->actingAs($user)
            ->get(route('calendar'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk();
    }
}
