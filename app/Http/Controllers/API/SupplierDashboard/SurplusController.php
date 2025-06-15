<?php

namespace App\Http\Controllers\API\SupplierDashboard;

use App\Enums\SurplusStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Surpluses\SurplusSearchRequest;
use App\Http\Requests\SupplierDashboard\Surpluses\CreateSurplusRequest;
use App\Http\Resources\SupplierDashboard\Surpluses\SurplusCollection;
use App\Models\Surplus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SurplusController extends Controller
{

    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(SurplusSearchRequest $request)
    {
        // ensure the user have permission to do that
        $data = $request->validated();
        // get all approved surpluses
        $surpluses = Surplus::searchSurpluses($data, SurplusStatus::Approved->value);
        return $this->successResponse(new SurplusCollection($surpluses), 'Surpluses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSurplusRequest $request)
    {
        try {
            $data = $request->validated();
            $data['supplier_id'] = Auth::id();

        } catch (\Exception $exception) {
            Log::error('store surplus' . $exception->getMessage());
            return $this->failureResponse('Unable to create surplus.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
