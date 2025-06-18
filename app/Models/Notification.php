<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{

    protected $fillable = ['type', 'data', 'read_at'];

    public function notifiable() : MorphTo {
        return $this->morphTo();
    }

}
