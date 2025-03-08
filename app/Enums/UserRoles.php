<?php

namespace App\Enums;

enum UserRoles : string 
{
    case Admin = 'admin';
    case Supplier = 'supplier';
    case Charity = 'charity';
}
