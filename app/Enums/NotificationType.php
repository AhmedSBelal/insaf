<?php

namespace App\Enums;

enum NotificationType: string
{
    // Supplier Notifications
    case SupplierApproved = 'supplier_approved';
    case SupplierPending = 'supplier_pending';
    case SupplierRejected = 'supplier_rejected';
    case SupplierAccountDeleted = 'supplier_account_deleted';

    // Charity Notifications
    case CharityApproved = 'charity_approved';
    case CharityPending = 'charity_pending';
    case CharityRejected = 'charity_rejected';
    case CharityAccountDeleted = 'charity_account_deleted';

    // Admin Notifications
    case AdminAccountDeleted = 'admin_account_deleted';
}
