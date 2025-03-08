<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{

    protected $fillable = [
        'url',
        'type'
    ];

    // relations
    public function imageable() :morphTo {
        return $this->morphTo();
    }

}
