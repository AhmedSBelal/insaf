<?php

namespace App\Http\Resources\APP\Categories;

use App\Http\Resources\APP\Surpluses\SurplusCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{

    public function imageResource($path) {
        return Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($path);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->imageResource($this->image->url),
            'surpluses' => new SurplusCollection($this->surpluses),
        ];
    }
}
