<?php

namespace App\Http\Resources\API\Supplier;

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
        // dd($this->supplier->commercialRegisters->pathurl, $this->supplier->healthCertificates->path_url);
        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->supplier->phone_number,
            'role' => $this->roles->first()->name,
            'commercial_register' => $this->supplier->commercialRegisters->pathurl ?? "Not Found",
            'health_certificate' => $this->supplier->healthCertificates->path_url ?? "Not Found" ,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'updated_at_human' => $this->updated_at->diffForHumans(),
        ];
    }
}
