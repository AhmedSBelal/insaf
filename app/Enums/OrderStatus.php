<?php

namespace App\Enums;

enum OrderStatus : string
{
    case Pending = 'Pending';
    case Processing = 'Processing';
    case Delivering = 'Delivering';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
    case Refunded = 'Refunded';
}
