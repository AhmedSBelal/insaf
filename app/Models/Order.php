<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function surpluses()
    {
        return $this->belongsToMany(Surplus::class, 'order_surplus');
    }

    public function charity()
    {
        return $this->belongsTo(Charity::class);
    }
}
