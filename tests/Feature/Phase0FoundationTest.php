<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Phase0FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_boots_successfully(): void
    {
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('MyTasks', false);
        $response->assertSee('Personal Daily Task Manager', false);
        $response->assertSee('data-testid="home-hero"', false);
    }

    public function test_vite_config_uses_bootstrap_entry_points(): void
    {
        $viteConfig = File::get(base_path('vite.config.js'));

        $this->assertStringContainsString("input: ['resources/css/app.css', 'resources/js/app.js']", $viteConfig);
        $this->assertStringNotContainsString('@tailwindcss/vite', $viteConfig);
        $this->assertStringNotContainsString('tailwindcss()', $viteConfig);

        $css = File::get(resource_path('css/app.css'));
        $js = File::get(resource_path('js/app.js'));
        $packageJson = File::json(base_path('package.json'));

        $this->assertStringContainsString('bootstrap/dist/css/bootstrap.min.css', $css);
        $this->assertStringContainsString('bootstrap-icons', $css);
        $this->assertStringContainsString('sweetalert2', $js);
        $this->assertArrayHasKey('bootstrap', $packageJson['dependencies'] ?? []);
        $this->assertArrayHasKey('bootstrap-icons', $packageJson['dependencies'] ?? []);
        $this->assertArrayHasKey('sweetalert2', $packageJson['dependencies'] ?? []);
    }

    public function test_migrations_run_successfully(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('jobs'));
        $this->assertTrue(Schema::hasColumns('users', ['id', 'name', 'email', 'password']));
        $this->assertDatabaseCount('users', 0);
    }

    public function test_foundation_directories_exist(): void
    {
        $directories = [
            app_path('Enums'),
            app_path('Policies'),
            app_path('Services'),
            app_path('Http/Controllers/Auth'),
            app_path('Http/Requests/Auth'),
            resource_path('views/layouts'),
            resource_path('views/components'),
            resource_path('views/partials'),
        ];

        foreach ($directories as $directory) {
            $this->assertDirectoryExists($directory);
        }

        $this->assertFileExists(resource_path('views/layouts/app.blade.php'));
        $this->assertFileExists(resource_path('views/layouts/guest.blade.php'));
    }

    public function test_pagination_uses_bootstrap_five(): void
    {
        $this->assertSame('pagination::bootstrap-5', Paginator::$defaultView);
    }
}
