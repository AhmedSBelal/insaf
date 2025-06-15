<?php

namespace App\Enums;

enum SupplierStatus : string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
