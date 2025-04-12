<?php

namespace App\Models;

use App\Enums\CharityStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Charity extends Model
{

    protected $fillable = [
        'id',
        'charity_id',
        'admin_id',
        'phone_number',
        'status',
    ];

    // relations
    public function info() : BelongsTo {
        return $this->belongsTo(User::class, 'charity_id', 'id');
    }

    public function admins() : BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function locations() : MorphMany{
        return $this->morphMany(Location::class, 'locationable');
    }

    public function commercialRegisters() : MorphMany{
        return $this->morphMany(Image::class, 'imageable');
    }

    // methods
    public function isApproved() : bool {
        return $this->status == CharityStatus::Approved->value ?? false;
    }

}
