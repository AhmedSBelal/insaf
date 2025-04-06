<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function generateVerificationCode()
    {
        $this->email_verification_code = rand(1000, 9999);
        $this->save();
    }

    public function verifyEmailWithCode($code)
    {
        if ($this->email_verification_code === $code) {
            $this->email_verified_at = now();
            $this->email_verification_code = null;
            $this->save();
            return true;
        }
        return false;
    }

    // relations
    public function admin(): HasOne {
        return $this->hasOne(Admin::class, 'admin_id', 'id');
    }

    public function supplier(): HasOne {
        return $this->hasOne(Supplier::class, 'supplier_id', 'id');
    }

    public function charity(): HasOne {
        return $this->hasOne(Charity::class, 'charity_id', 'id');
    }

    public function locations(): MorphMany {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function images(): MorphMany {
        return $this->morphMany(Image::class, 'imageable');
    }

}
