<?php

namespace App\Enums;

enum UserRoles : string
{
    case SuperAdmin = 'superadmin';
    case Admin = 'admin';
    case Supplier = 'supplier';
    case Charity = 'charity';

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
