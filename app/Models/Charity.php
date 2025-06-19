<?php

namespace App\Models;

use App\Enums\CharityStatus;
use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    public function commercialRegisters() : MorphOne{
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', ImageType::CommercialRegister->value);
    }

    // methods
    public function isApproved() : bool {
        return $this->status == CharityStatus::Approved->value ?? false;
    }

    public static function charitiesSearch($filter) {

        $query = self::with('info');

        // Filter on Supplier table (direct attributes)
        if (isset($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        // Filter on related info table
//        if (isset($filters['status'])) {
//            $query->whereHas('info', fn($q) =>
//            $q->where('status', $filters['status'])
//            );
//        }

        return $query->paginate(16);

    }

    public function rating () : HasMany {
        return $this->hasMany(SupplierRating::class, 'charity_id', 'id');
    }

}
