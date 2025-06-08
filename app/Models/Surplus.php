<?php

namespace App\Models;

use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;

class Surplus extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'admin_id',
        'category_id',
        'name',
        'description',
        'quantity',
        'price',
        'expire_date',
        'status',
    ];

    // relation ships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location() :MorphOne{
        return $this->morphOne(Location::class, 'locationable');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_surplus')
            ->withPivot(['quantity', 'price', 'surplus_name'])
            ->withTimestamps();
    }


    // methods

    public function coverImage()
    {
        $path = null;
        $image = $this->images()->where('type', ImageType::Cover->value)->first();
        if ($image) {
            $path = Storage::disk('public')->exists($image->url) ? 'storage/' . $image->url : $image->url;
        }
        return $path ? asset($path) : $path;
    }

    public function exceptCoverImage()
    {
        return $this->images()
            ->whereNot('type', ImageType::Cover->value)
            ->get()
            ->map(function ($image) {
                $path = Storage::disk('public')->exists($image->url) ? 'storage/' . $image->url : $image->url;
                return asset($path);
            })
            ->toArray();
    }

    public static function searchSurpluses(array $attributes, $status = false)
    {
        $query = self::query();

        if ($status) {
            $query->where('status', $status);
        }

        if (isset($attributes['title'])) {
            $query->where('title', 'like', '%' . $attributes['title'] . '%');
        }
        if (isset($attributes['description'])) {
            $query->where('description', 'like', '%' . $attributes['description'] . '%');
        }
        if (isset($attributes['quantity'])) {
            $query->where('quantity', $attributes['quantity']);
        }
        if (isset($attributes['price'])) {
            $query->where('price', '<=', $attributes['price']);
        }
        if (isset($attributes['expire_date'])) {
            $query->whereDate('expire_date', '>=', $attributes['expire_date']);
        }
        if (isset($attributes['category'])) {
            $query->where('category_id', $attributes['category']);
        }

        return $query->paginate(16);
    }


}
