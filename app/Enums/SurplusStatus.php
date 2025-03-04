<?php

namespace App\Enums;

enum SurplusStatus : string
{
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}
