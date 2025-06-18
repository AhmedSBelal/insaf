<?php

namespace App\Http\Resources\APP;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->charity->phone_number,
            'status' => $this->charity->status,
            'profile_image' => $this->profile_image_url
        ];
    }
}
