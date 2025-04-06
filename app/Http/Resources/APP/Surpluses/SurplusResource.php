<?php

namespace App\Http\Resources\APP\Surpluses;

use App\Enums\ImageType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SurplusResource extends JsonResource
{

    public function coverImageResource()
    {
        $path = $this->images->where('type', ImageType::Cover->value)->first()->url;
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return asset($path);
    }

    public function imagesResource(): array
    {
        return $this->images
            ->reject(fn($image) => $image->type == ImageType::Cover->value)
            ->map(fn($image) => asset(Storage::disk('public')->exists($image->url) ? 'storage/' . $image->url : $image->url))
            ->toArray();
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
            'cover_image' => $this->coverImageResource(),
            'price' => $this->price,
            'expire_date' => $this->expire_date,
            'images' => $this->imagesResource(),
            'location' => $this->location->physical_location,
        ];
    }
}
