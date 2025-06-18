<?php

namespace App\Http\Resources\AdminDashboard\suppliers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SupplierCollection extends ResourceCollection
{

    public function imageResource($path) {
        return $path == null ? null : (Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($path));
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'suppliers' => $this->collection->map(function ($supplier) {
                return [
                    'id' => $supplier->supplier_id,
                    'profile_image' => $this->imageResource($supplier->info->profile_image ?? null),
                    'name' => $supplier->info->name ?? null,
                    'email' => $supplier->info->email ?? null,
                    'phone' => $supplier->phone_number,
                    'type' => $supplier->type,
                    'status' => $supplier->status,
                ];
            }),
            'meta' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? [
                    'total_items' => $this->resource->total(),
                    'items_per_page' => $this->resource->perPage(),
                    'current_page' => $this->resource->currentPage(),
                    'first_page_url' => $this->resource->url(1),
                    'last_page_url' => $this->resource->url($this->resource->lastPage()),
                    'next_page_url' => $this->resource->nextPageUrl(),
                    'prev_page_url' => $this->resource->previousPageUrl(),
                    'from' => $this->resource->firstItem(),
                    'to' => $this->resource->lastItem(),
                ]
                : null,
        ];
    }
}
