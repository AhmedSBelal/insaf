<?php

namespace App\Http\Resources\AdminDashboard\Suppliers;

<<<<<<< HEAD
use App\Models\Location;
=======
>>>>>>> master
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SupplierResource extends JsonResource
{
    public function imageResource($path) {
        return $path == null ?
            null : (Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($path));
    }


    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
<<<<<<< HEAD
//        dd(Location::all());
=======
>>>>>>> master
        // Return empty array if resource is null
        if (!$this->resource) {
            return [];
        }

        return [
            'name' => $this->info->name ?? null,
            'email' => $this->info->email ?? null,
            'phone' => $this->phone_number ?? null,
            'commercial_registers' => $this->imageResource($this->commercialRegisters->url ?? null),
            'health_certificates' => $this->imageResource($this->healthCertificates->url ?? null),
<<<<<<< HEAD
            'status' => $this->status,
            'address' => $this->location->physical_location ?? null,
//            'password' => $this->info->password ?? null,
        'password' => "********************************"
=======
            'status' => $this->status
>>>>>>> master
        ];
    }
}
