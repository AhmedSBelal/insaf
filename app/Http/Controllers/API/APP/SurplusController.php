<?php

namespace App\Http\Controllers\API\APP;

use App\Enums\SurplusStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Surpluses\SurplusSearchRequest;
use App\Http\Resources\APP\Surpluses\SurplusCollection;
use App\Http\Resources\APP\Surpluses\SurplusResource;
use App\Models\Surplus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SurplusController extends Controller
{

    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(SurplusSearchRequest $request)
    {
        $data = $request->all();
        // get all approved surpluses
        $surpluses = Surplus::searchSurpluses($data, SurplusStatus::Approved->value);
        return $this->successResponse(new SurplusCollection($surpluses), 'Surpluses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surplus = Surplus::with(['location', 'images'])->find($id);
        if (!$surplus) {
            return $this->failureResponse('Surplus not found', 404);
        }
        return $this->successResponse(new SurplusResource($surplus), 'Surplus retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
