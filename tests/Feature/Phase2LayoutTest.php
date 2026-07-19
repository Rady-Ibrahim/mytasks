<?php

namespace Tests\Feature;

use App\Enums\Theme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase2LayoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_authenticated_user_sees_app_layout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-testid="app-sidebar"', false)
            ->assertSee('data-testid="app-topnav"', false)
            ->assertSee('data-testid="sidebar-nav"', false)
            ->assertSee('data-testid="theme-toggle"', false)
            ->assertSee('data-testid="loading-indicator"', false);
    }

    public function test_guest_is_redirected_from_app_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->post(route('theme.update'))->assertRedirect(route('login'));
    }

    public function test_theme_toggle_updates_user_preference(): void
    {
        $user = User::factory()->create([
            'theme' => Theme::Light,
        ]);

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('theme.update'), [
                'theme' => Theme::Dark->value,
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertSame(Theme::Dark, $user->fresh()->theme);

        $this->actingAs($user)
            ->post(route('theme.update'), [
                'theme' => Theme::Light->value,
            ]);

        $this->assertSame(Theme::Light, $user->fresh()->theme);
    }

    public function test_dark_theme_class_is_rendered_when_preferred(): void
    {
        $user = User::factory()->create([
            'theme' => Theme::Dark,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('data-bs-theme="dark"', false);
    }

    public function test_light_theme_is_default_for_new_users(): void
    {
        $user = User::factory()->create();

        $this->assertSame(Theme::Light, $user->theme);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertSee('data-bs-theme="light"', false);
    }
}
