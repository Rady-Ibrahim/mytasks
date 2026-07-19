<?php

namespace Tests\Feature;

use App\Enums\Locale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_can_switch_to_english(): void
    {
        $this->from(route('login'))
            ->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Welcome back', false)
            ->assertSee('dir="ltr"', false)
            ->assertSee('data-testid="locale-toggle"', false);
    }

    public function test_guest_can_switch_back_to_arabic(): void
    {
        $this->withSession(['locale' => 'en'])
            ->from(route('login'))
            ->post(route('locale.update'), ['locale' => 'ar'])
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('مرحبًا بعودتك', false)
            ->assertSee('dir="rtl"', false);
    }

    public function test_authenticated_user_locale_is_persisted(): void
    {
        $user = User::factory()->create([
            'locale' => Locale::Arabic,
        ]);

        $this->actingAs($user)
            ->from(route('dashboard'))
            ->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect(route('dashboard'));

        $this->assertSame(Locale::English, $user->fresh()->locale);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('dir="ltr"', false);
    }

    public function test_invalid_locale_is_rejected(): void
    {
        $this->from(route('login'))
            ->post(route('locale.update'), ['locale' => 'fr'])
            ->assertSessionHasErrors('locale');
    }
}
