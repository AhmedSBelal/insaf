<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = [
        'charity_id',
        'supplier_id',
        'payment_id',
        'payment_status',
        'total_price',
        'status',
    ];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'status' => OrderStatus::class,
    ];

    public function charity(): BelongsTo
    {
        return $this->belongsTo(Charity::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function surpluses(): BelongsToMany
    {
        return $this->belongsToMany(Surplus::class, 'order_surplus')
            ->withPivot(['quantity', 'price', 'surplus_name'])
            ->withTimestamps();
    }
}
