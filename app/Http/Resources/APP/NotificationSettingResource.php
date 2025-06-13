<?php

namespace App\Http\Resources\APP;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'general_notification' => $this->general_notification,
            'order_updates' => $this->order_updates,
            'promotions_offers' => $this->promotions_offers,
            'announcements' => $this->announcements,
            'call_sound' => $this->call_sound,
            'vibration' => $this->vibration,
            'notification_types' => $this->notification_types,
        ];
    }
}
