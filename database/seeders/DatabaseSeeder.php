<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(DefaultCategoryService $defaultCategories): void
    {
        $testUser = User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'theme' => 'light',
                'email_verified_at' => now(),
            ]
        );

        $defaultCategories->seedFor($testUser);

        $this->call([
            DemoSeeder::class,
        ]);
    }
}
