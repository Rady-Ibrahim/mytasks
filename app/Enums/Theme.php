<?php

namespace App\Enums;

enum Theme: string
{
    case Light = 'light';
    case Dark = 'dark';

    public function label(): string
    {
        return match ($this) {
            self::Light => __('Light'),
            self::Dark => __('Dark'),
        };
    }

    public function toggle(): self
    {
        return $this === self::Light ? self::Dark : self::Light;
    }
}
