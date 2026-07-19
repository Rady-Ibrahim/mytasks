<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::InProgress => __('In Progress'),
            self::Completed => __('Completed'),
            self::Cancelled => __('Cancelled'),
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'text-bg-secondary',
            self::InProgress => 'text-bg-primary',
            self::Completed => 'text-bg-success',
            self::Cancelled => 'text-bg-dark',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
