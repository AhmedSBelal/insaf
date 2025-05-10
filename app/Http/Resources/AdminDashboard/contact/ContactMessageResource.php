<?php

namespace App\Http\Resources\AdminDashboard\contact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'message' => $this->message ?? null,
        ];
    }
}
