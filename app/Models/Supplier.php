<?php

namespace App\Models;

use App\Enums\CharityStatus;
use App\Enums\ImageType;
use App\Enums\SupplierStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Supplier extends Model
{

    protected $fillable = [
        'id',
        'supplier_id',
        'admin_id',
        'phone_number',
        'status',
    ];

    // relations
    public function info (): BelongsTo {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function admins(): BelongsTo {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function surplus(): HasMany {
        return $this->hasMany(Surplus::class);
    }

    public function commercialRegisters(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function healthCertificates(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    // methods
    public function isApproved() : bool {
        return $this->status == CharityStatus::Approved->value ?? false;
    }

    public static function suppliersSearch($filter) {

        $query = self::with('info');

        // Filter on Supplier table (direct attributes)
        if (isset($filter['status'])) {
            $query->where('status', $filter['status']);
        }
        if (isset($filter['type'])) {
            $query->where('type', $filter['type']);
        }

        // Filter on related info table
//        if (isset($filters['status'])) {
//            $query->whereHas('info', fn($q) =>
//            $q->where('status', $filters['status'])
//            );
//        }

        return $query->paginate(16);

    }

    public  function location()
    {
        return $this->morphOne(Location::class, 'locationable');
    }


}
