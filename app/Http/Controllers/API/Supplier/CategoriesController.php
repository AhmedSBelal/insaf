<?php

namespace App\Http\Controllers\API\Supplier;

use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Supplier\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends APIBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::all();

        return $this->successResponse(
            CategoriesResource::collection($categories),
            'Categories retrieved successfully.'
        );
    }
}
