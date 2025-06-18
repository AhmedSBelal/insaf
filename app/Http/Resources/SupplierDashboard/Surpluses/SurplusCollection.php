<?php

namespace App\Http\Resources\SupplierDashboard\Surpluses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurplusCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'surpluses' => $this->collection->map(function ($surplus) {
                return [
                    'id' => $surplus->id,
                    'name' => $surplus->name,
                    'category' => $surplus->category?->name,
                    'price' => $surplus->price,
                    'expiry_date' => $surplus->expire_date,
                    'status' => $surplus->status,
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
