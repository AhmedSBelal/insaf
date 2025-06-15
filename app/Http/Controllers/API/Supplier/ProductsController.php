<?php

namespace App\Http\Controllers\API\Supplier;

use App\Enums\ImageType;
use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Supplier\StoreProductRequest;
use App\Http\Resources\API\Supplier\ProductsResource;
use Illuminate\Http\Request;

class ProductsController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd("f");
        $products = auth()->user()->supplier->surplus()
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
            // dd($products->toArray());

        return $this->successResponse(
            ProductsResource::collection($products)->response()
                ->getData(true),
            'Products retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        unset($data['image']);

        $product = auth()->user()->supplier->surplus()->create($data);

        $images = $request->file('images');
        if ($images) {
            foreach ($images as $image) {
                $product->images()->create([
                    'url' => $this->uploadFile($image, 'products'),
                    'type' => ImageType::Cover->value,
                ]);
            }
        }

        return $this->successResponse(
            new ProductsResource($product),
            'Product created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd(auth()->user()->supplier->surpluses()->());
        $product = auth()->user()->supplier->surplus()->with(['category', 'images'])->findOrFail($id);

        return $this->successResponse(
            new ProductsResource($product),
            'Product retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, string $id)
    {
        $data = $request->validated();
        unset($data['image']);

        $product = auth()->user()->supplier->surplus()->findOrFail($id);
        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->images()->create([
                    'url' => $this->uploadFile($image, 'products'),
                    'type' => ImageType::Cover->value,
                ]);
            }
        }

        return $this->successResponse(
            new ProductsResource($product),
            'Product updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = auth()->user()->supplier->surplus()->findOrFail($id);
        $product->images()->delete();
        $product->delete();

        return $this->successResponse([], 'Product deleted successfully.');
    }
}
