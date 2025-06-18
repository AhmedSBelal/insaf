<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'general_notification',
        'order_updates',
        'promotions_offers',
        'announcements',
        'call_sound',
        'vibration',
        'notification_types'
    ];

    protected $casts = [
        'notification_types' => 'array',
        'general_notification' => 'boolean',
        'order_updates' => 'boolean',
        'promotions_offers' => 'boolean',
        'announcements' => 'boolean',
        'vibration' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
