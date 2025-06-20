<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\SupplierRatingRequest;
use App\Models\SupplierRating;
use Illuminate\Http\Request;

class SupplierRatingController extends Controller
{
    public function store(SupplierRatingRequest $request)
    {
        $data = $request->validated();
        $data['charity_id'] = auth()->id();

        $rating = SupplierRating::updateOrCreate(
            ['supplier_id' => $data['supplier_id'], 'charity_id' => $data['charity_id']],
            ['rating' => $data['rating'], 'comment' => $data['comment']]
        );

        return response()->json([
            'message' => 'Supplier rating submitted successfully.',
            'data' => $rating
        ]);
    }

    public function index()
    {
        $ratings = SupplierRating::with(['supplier', 'charity'])->get();
        return response()->json([
            'data' => $ratings
        ]);
    }
}
