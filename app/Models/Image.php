<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{

    protected $fillable = [
        'url',
        'type',
        'imageable_id',
        'imageable_type',
        'created_at',
        'updated_at',
    ];

    // relations
    public function imageable() :morphTo {
        return $this->morphTo();
    }

}
