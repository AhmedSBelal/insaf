<?php

namespace App\Enums;

enum CharityStatus: string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}