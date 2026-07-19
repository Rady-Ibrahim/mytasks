<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class Phase1AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_can_view_register_and_login_pages(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Create your account');
        $this->get(route('login'))->assertOk()->assertSee('Welcome back');
    }

    public function test_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ada@example.com',
            'name' => 'Ada Lovelace',
        ]);
    }

    public function test_registration_validation_fails_for_invalid_data(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $login = $this->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $login->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $logout = $this->post(route('logout'));

        $logout->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_password_reset_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user): bool {
            $response = $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);

            $response->assertRedirect(route('login'));
            $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));

            return true;
        });
    }

    public function test_authenticated_users_are_redirected_from_guest_auth_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($user)
            ->get(route('register'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Welcome, '.$user->name, false);
    }
}
