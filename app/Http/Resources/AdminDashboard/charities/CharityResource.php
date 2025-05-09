<?php

namespace App\Http\Resources\AdminDashboard\charities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class CharityResource extends JsonResource
{

    public function imageResource($path) {
        return $path == null ? null : (Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($path));
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'id' => $this->id,
            'name' => $this->info->name ?? null,
            'email' => $this->info->email ?? null,
            'phone' => $this->phone_number ?? null,
            'commercial_registers' => $this->imageResource($this->commercialRegisters->url ?? null),
            'status' => $this->status
        ];
    }
}
