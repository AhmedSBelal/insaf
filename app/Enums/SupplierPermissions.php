<?php

namespace App\Enums;

enum SupplierPermissions : string
{
    //

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
