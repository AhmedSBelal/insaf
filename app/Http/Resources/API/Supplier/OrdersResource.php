<?php

namespace App\Http\Resources\API\Supplier;

use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//         return parent::toArray($request);

        $supplier = Supplier::where("supplier_id", $this->surpluses->first()->supplier_id)->first() ?? null;
    //    dd($);

        return [
            'id' => $this->id,
            'product' => $this->surpluses->pluck('name')->implode(', '),
            'date' => $this->created_at->format('d M Y'),
            'status' => $this->status,
            'products' => $this->surpluses ?? null,
            'supplier' => $supplier->info ?? null,
            "supplier_location" => Location::where('locationable_id', $supplier->id)
            ->where('locationable_type', 'App\Models\Supplier')
            ->first()->physical_location??"No Location Found",
            'charity' => $this->charity->info ?? null,
            "charity_location" => Location::where('locationable_id', $this->charity->id)
            ->where('locationable_type', 'App\Models\Charity')
            ->first()->physical_location??"No Location Found",
        ];
    }
}
