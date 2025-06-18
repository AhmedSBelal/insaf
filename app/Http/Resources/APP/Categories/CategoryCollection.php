<?php

namespace App\Http\Resources\APP\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class CategoryCollection extends ResourceCollection
{

    public function imageResource($path) {
        return Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($path);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'categories' => $this->collection->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image' => $this->imageResource($category->image->url),
                ];
            }),
        ];
    }
}
