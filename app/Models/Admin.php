<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{

    protected $fillable = [
        'admin_id',
        'type',
    ];

    // relations
    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function suppliers(): HasMany {
        return $this->hasMany(Supplier::class, 'admin_id', 'id');
    }

    public function charities(): HasMany {
        return $this->hasMany(Charity::class, 'admin_id', 'id');
    }

}
