<?php

namespace App\Models;

use App\Enums\PaymentMethodes;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'payment_method' => PaymentMethodes::class,
        'status' => PaymentStatus::class,
    ];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
