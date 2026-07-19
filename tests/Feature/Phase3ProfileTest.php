<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase3ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'New Name',
                'email' => 'new@example.com',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_email_uniqueness_is_enforced(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create(['email' => 'mine@example.com']);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'taken@example.com',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('email');
    }

    public function test_password_change_requires_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.password'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('current_password');

        $this->actingAs($user)
            ->put(route('profile.password'), [
                'current_password' => 'password123',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_avatar_upload_stores_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $file,
            ])
            ->assertRedirect();

        $user->refresh();

        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_profile_routes_require_authentication(): void
    {
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
        $this->put(route('profile.update'))->assertRedirect(route('login'));
        $this->put(route('profile.password'))->assertRedirect(route('login'));
    }
}
