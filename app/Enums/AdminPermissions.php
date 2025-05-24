<?php

namespace App\Enums;

enum AdminPermissions : string
{

    case ShowSuppliers = "ShowSuppliers";
    case ShowSupplierDetails = "ShowSupplierDetails";
    case UpdateSupplier = 'UpdateSupplier';
    case DeleteSupplier = 'DeleteSupplier';
    case ApproveSupplier = 'ApproveSupplier';

    case ShowCharities = "ShowCharities";
    case ShowCharityDetails = "ShowCharityDetails";
    case UpdateCharity = 'UpdateCharity';
    case DeleteCharity = 'DeleteCharity';
    case ApproveCharity = 'ApproveCharity';
    case ReplyOnContactMessages = 'ReplyOnContactMessages';

    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
