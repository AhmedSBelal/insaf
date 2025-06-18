<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Supplier\ProductsResource;
use App\Models\Surplus;
use Illuminate\Http\Request;

class ProductController extends APIBaseController
{
    public function index(Request $request)
    {
        $products = Surplus::query()
            ->when($request->has('categories'), function ($query) use ($request) {
                $categories = explode(',', $request->categories);
                return $query->whereIn('category_id', $categories);
            })
            ->when($request->has('status'), function ($query) use ($request) {
                $status = $request->status;
                if ($status === 'expired') {
                    return $query->where('expire_date', '<=', now());
                } elseif ($status === 'available') {
                    return $query->where('expire_date', '>', now())
                        ->where('quantity', '>', 0);
                } elseif ($status === 'out_of_stock') {
                    return $query->where('quantity', '<=', 0);
                }
            })
            ->when($request->has('expiry_date'), function ($query) use ($request) {
                return $query->where('expire_date', $request->expiry_date);
            })
            ->with(['category'])
            ->paginate(10);

//        dd($products);
        return $this->successResponse(
            ProductsResource::collection($products)->response()
                ->getData(true),
            'Products retrieved successfully.'
        );
    }


    public  function show($id)
    {
        $product = Surplus::with(['category', 'images'])->findOrFail($id);
        // dd($product);
        return $this->successResponse(
            new ProductsResource($product),
            'Product retrieved successfully.'
        );

    }

    public function destroy(string $id)
    {
        $product = Surplus::findOrFail($id);
        $product->images()->delete();
        $product->delete();

        return $this->successResponse([], 'Product deleted successfully.');
    }
}
