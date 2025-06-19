<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierRating extends Model
{
    protected $fillable = ['supplier_id', 'charity_id', 'rating', 'comment'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function charity()
    {
        return $this->belongsTo(User::class, 'charity_id');
    }
}
