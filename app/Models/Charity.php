<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charity extends Model
{

    protected $fillable = [
        'admin_id',
        'phone_number',
    ];

    // relations
    public function users() : BelongsTo {
        return $this->belongsTo(User::class, 'charity_id', 'id');
    }

    public function admins() : BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

}
