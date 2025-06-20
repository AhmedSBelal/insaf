<?php

namespace App\Enums;

enum SuppliersType : string
{
    case Factory = 'factory';
    case Restaurant = 'restaurant';
    public static function values(): array{
        return array_column(self::cases(), 'value');
    }
}
