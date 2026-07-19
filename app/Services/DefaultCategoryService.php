<?php

namespace App\Services;

use App\Models\User;

class DefaultCategoryService
{
    /**
     * @var list<array{name: string, color: string, icon: string}>
     */
    public const DEFAULTS = [
        ['name' => 'Work', 'color' => '#0d6efd', 'icon' => 'bi-briefcase'],
        ['name' => 'Study', 'color' => '#6f42c1', 'icon' => 'bi-book'],
        ['name' => 'Personal', 'color' => '#198754', 'icon' => 'bi-person'],
        ['name' => 'Health', 'color' => '#dc3545', 'icon' => 'bi-heart-pulse'],
        ['name' => 'Shopping', 'color' => '#fd7e14', 'icon' => 'bi-cart3'],
        ['name' => 'Finance', 'color' => '#20c997', 'icon' => 'bi-wallet2'],
    ];

    public function seedFor(User $user): void
    {
        foreach (self::DEFAULTS as $category) {
            $user->categories()->firstOrCreate(
                ['name' => $category['name']],
                [
                    'color' => $category['color'],
                    'icon' => $category['icon'],
                ]
            );
        }
    }
}
