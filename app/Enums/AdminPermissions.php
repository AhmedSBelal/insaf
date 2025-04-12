<?php

namespace App\Enums;

enum AdminPermissions : string
{

    case ApproveSupplier = 'ApproveSupplier';

    case ApproveCharity = 'ApproveCharity';
    case RemoveCharity = 'DeleteCharity';

    case ShowSuppliers = "ShowSuppliers";
    case ShowSupplierDetails = "ShowSupplierDetails";
    case UpdateSupplier = 'UpdateSupplier';
    case DeleteSupplier = 'DeleteSupplier';

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
