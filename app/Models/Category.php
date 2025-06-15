<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'parent_id', 'name', 'description'];

    // relationships
    public function surpluses(): HasMany
    {
        return $this->hasMany(Surplus::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

}
