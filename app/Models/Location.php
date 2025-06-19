<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Location extends Model
{

    protected $fillable = [
        'physical_location', 'latitude', 'longitud',
    ];

    // relations
    public function locationable() :morphTo {
        return $this->morphTo();
    }

}
