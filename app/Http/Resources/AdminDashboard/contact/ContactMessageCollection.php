<?php

namespace App\Http\Resources\AdminDashboard\contact;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactMessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Messages' => $this->collection->map(function ($message) {
                return [
                    'id' => $message->id,
                    'name' => $message->name ?? null,
                    'email' => $message->email ?? null,
                    'phone' => $message->phone,
                    'message' => $message->message,
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
