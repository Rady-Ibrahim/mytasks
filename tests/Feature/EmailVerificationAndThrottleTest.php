<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationAndThrottleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_unverified_user_is_redirected_from_dashboard(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_verify_email_with_signed_link(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect();

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_verification_email_can_be_resent(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_register_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('register'), [
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])->assertRedirect();

            auth()->logout();
            $this->flushSession();
        }

        $this->post(route('register'), [
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(429);
    }

    public function test_password_reset_request_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'reset@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('password.email'), [
                'email' => 'reset@example.com',
            ]);
        }

        $this->post(route('password.email'), [
            'email' => 'reset@example.com',
        ])->assertStatus(429);
    }

    public function test_arabic_locale_is_active(): void
    {
        $this->assertSame('ar', app()->getLocale());

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('Welcome back'))
            ->assertSee('dir="rtl"', false);
    }
}
