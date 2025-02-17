<?php

namespace App\Enums;

enum SupplierStatus : string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
