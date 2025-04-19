<?php

namespace App\Http\Resources\AdminDashboard\charities;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class CharityCollection extends ResourceCollection
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
            'charities' => $this->collection->map(function ($charity) {
                return [
                    'id' => $charity->id,
                    'profile_image' => $this->imageResource($charity->info->profile_image ?? null),
                    'name' => $charity->info->name ?? null,
                    'email' => $charity->info->email ?? null,
                    'phone' => $charity->phone_number,
                    'type' => $charity->type,
                    'status' => $charity->status,
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
