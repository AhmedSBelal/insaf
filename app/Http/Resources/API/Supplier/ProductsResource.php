<?php

namespace App\Http\Resources\API\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd(Supplier::where("supplier_id", $this->supplier_id)->with("location")->first());
        // dd("f");
        // $supplier = Supplier::with('location')->find(5);
    //    dd(Supplier::where("supplier_id" , $this->supplier_id)->with("location")->first()->location->physical_location ?? "No Location Found");
        $request->routeIs('supplier.products.index') ? $name = $this->name . ' (' . $this->quantity . ' Units)' : $name = $this->name;

        return
        [
            'id' => $this->id,
            'name' => $name,
            'description' => $request->routeIs('supplier.products.index') ? null : $this->description,
            'quantity' => $request->routeIs('supplier.products.index') ? null : $this->quantity,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->path_url,
                    ];
                });
            }),
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            'price' => number_format($this->price, 2),
            'status' => $this->status,
            'location' => Supplier::where("supplier_id", $this->supplier_id)->with("location")->first()->location->physical_location ?? "No Location Found",
            'expire_date' => $this->expire_date,
            'expiry_date_formatted' => $this->formatExpiryDate($this->expire_date),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'updated_at_human' => $this->updated_at->diffForHumans(),
        ];
    }

    protected function formatExpiryDate($expire_date)
    {
        if (!$expire_date) {
            return null;
        }

        $now = now();
        $expiry = \Carbon\Carbon::parse($expire_date);

        if ($expiry->lessThanOrEqualTo($now)) {
            return 'Expired';
        }

        $diff = $now->diff($expiry);

        $parts = [];

        if ($diff->m > 0) {
            $parts[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
        }
        if ($diff->d >= 7) {
            $weeks = intdiv($diff->d, 7);
            $days = $diff->d % 7;
            if ($weeks > 0) {
                $parts[] = $weeks . ' week' . ($weeks > 1 ? 's' : '');
            }
            if ($days > 0) {
                $parts[] = $days . ' day' . ($days > 1 ? 's' : '');
            }
        } else if ($diff->d > 0) {
            $parts[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
        }

        if (empty($parts)) {
            return 'Today';
        }

        return 'Expires in ' . implode(', ', $parts);
    }

}
