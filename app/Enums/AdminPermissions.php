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
    case ShowContactMessages = 'ShowContactMessages';
    case ShowAdmins = 'ShowAdmins';
    case ShowAdminDetails = 'ShowAdminDetails';
    case UpdateAdmin = 'UpdateAdmin';
    case DeleteAdmin = 'DeleteAdmin';



    public static function values(): array{
        return array_column(self::cases(), 'value');
    }

}
