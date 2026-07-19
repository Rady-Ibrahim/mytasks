<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(DefaultCategoryService $defaultCategories): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ]
        );

        $defaultCategories->seedFor($user);
    }
}
