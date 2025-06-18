<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Resources\APP\Categories\CategoryCollection;
use App\Http\Resources\APP\Categories\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index() {
        $categories = Category::with(['image'])->get();
        return $this->successResponse(new CategoryCollection($categories), 'All Categories', 200);
    }

    public function show($id) {
        $category = Category::with(['image', 'surpluses'])->find($id);
        if (!$category) {
            return $this->failureResponse('Category not found', 404);
        }
        return $this->successResponse(new CategoryResource($category), 'Category', 200);
    }

}
