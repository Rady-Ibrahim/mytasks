<?php

namespace App\Enums;

enum Theme: string
{
    case Light = 'light';
    case Dark = 'dark';

    public function label(): string
    {
        return match ($this) {
            self::Light => 'Light',
            self::Dark => 'Dark',
        };
    }

    public function toggle(): self
    {
        return $this === self::Light ? self::Dark : self::Light;
    }
}
