<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{

    protected $fillable = [
        'supplier_id',
        'admin_id',
        'phone_number',
        'status',
    ];

    // relations
    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function admins(): BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function surplus(): HasMany {
        return $this->hasMany(Surplus::class);
    }

}
