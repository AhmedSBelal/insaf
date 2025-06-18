<?php

namespace App\Enums;

enum OrderStatus : string
{
    case Pending = 'Pending';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';

    public static function getValues(): string
    {
        return implode(',', array_map(fn($status) => $status->value, self::cases()));
    }
    case Processing = 'Processing';
    case Delivering = 'Delivering';
    case Refunded = 'Refunded';
    const Confirmed = 'Confirmed';
}
