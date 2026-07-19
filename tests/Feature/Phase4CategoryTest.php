<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase4CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('categories.store'), [
                'name' => 'Work',
                'color' => '#0d6efd',
                'icon' => 'bi-briefcase',
            ])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Work',
            'color' => '#0d6efd',
            'icon' => 'bi-briefcase',
        ]);
    }

    public function test_category_validation_rules(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => '',
                'color' => 'blue',
                'icon' => 'tag',
            ])
            ->assertRedirect(route('categories.create'))
            ->assertSessionHasErrors(['name', 'color', 'icon']);
    }

    public function test_user_can_update_and_soft_delete_own_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user)->create([
            'name' => 'Study',
        ]);

        $this->actingAs($user)
            ->put(route('categories.update', $category), [
                'name' => 'Learning',
                'color' => '#6f42c1',
                'icon' => 'bi-book',
            ])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Learning',
        ]);

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_user_cannot_access_another_users_category(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('categories.edit', $category))
            ->assertForbidden();

        $this->actingAs($intruder)
            ->put(route('categories.update', $category), [
                'name' => 'Hacked',
                'color' => '#000000',
                'icon' => 'bi-tag',
            ])
            ->assertForbidden();

        $this->actingAs($intruder)
            ->delete(route('categories.destroy', $category))
            ->assertForbidden();
    }

    public function test_index_lists_only_own_categories(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Category::factory()->for($user)->create(['name' => 'Mine']);
        Category::factory()->for($other)->create(['name' => 'Theirs']);

        $this->actingAs($user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Theirs');
    }

    public function test_registration_seeds_default_categories(): void
    {
        $this->post(route('register'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'newuser@example.com')->firstOrFail();

        $this->assertCount(count(DefaultCategoryService::DEFAULTS), $user->categories);
        $this->assertTrue($user->categories()->where('name', 'Work')->exists());
    }
}
