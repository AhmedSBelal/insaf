<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\ImageType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function is_supplier(): bool {
        return $this->supplier()->exists();
    }

    public function charity(): HasOne {
        return $this->hasOne(Charity::class, 'charity_id', 'id');
    }

    public function locations(): MorphMany {
        return $this->morphMany(Location::class, 'locationable');
    }

    public function profileImage(): MorphOne {
        return $this->morphOne(Image::class, 'imageable')
            ->where('type', ImageType::Profile->value);
    }

    public function notications(): MorphMany {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // app/Models/User.php
    public function notificationSetting()
    {
        return $this->hasOne(NotificationSettings::class);
    }

    // accessors
    protected function profileImageUrl() : Attribute {
        return Attribute::make(
            get: function () {
                if (! $this->relationLoaded('profileImage')) {
                    $this->load('profileImage');
                }
                return $this->profileImage?->url
                    ? asset('storage/' . $this->profileImage->url)
                    : asset('defaults/images/profile_image.jpg');
            },
        )->shouldCache();
    }

    /**
     * Auth With jWT
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function getPermissionFromRoles(){
        return $this->roles->first()->permissions;
    }

    public function getRoleName(){
        return $this->roles->first()->name;
    }
}
