<?php

namespace App\Enums;

enum Locale: string
{
    case Arabic = 'ar';
    case English = 'en';

    public function label(): string
    {
        return match ($this) {
            self::Arabic => 'العربية',
            self::English => 'English',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Arabic => 'ع',
            self::English => 'EN',
        };
    }

    public function toggle(): self
    {
        return $this === self::Arabic ? self::English : self::Arabic;
    }

    public function isRtl(): bool
    {
        return $this === self::Arabic;
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
