<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Cart\AddItemRequest;
use App\Http\Requests\APP\Cart\MergeCartRequest;
use App\Http\Requests\APP\Cart\UpdateItemRequest;
use App\Http\Resources\APP\Cart\CartItemResource;
use App\Http\Resources\APP\Cart\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    use ApiResponse;
    private function getOrCreateCart(Request $request)
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        // Ensure session_id is provided
        if (!$request->has('session_id') || empty($request->session_id)) {
            abort(400, 'Missing session_id for guest user.');
        }

        return Cart::firstOrCreate(['session_id' => $request->session_id]);
    }

    public function show(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart($request);
            return $this->successResponse(
                new CartResource($cart->load('items.surplus')),
                'Cart items retrieved successfully.'
            );
        } catch (\Exception $exception) {
            Log::error('show cart' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

    public function addItem(AddItemRequest $request)
    {
        try {
            DB::beginTransaction();
            $cart = $this->getOrCreateCart($request);

            $item = $cart->items()
                ->where('surplus_id', $request->surplus_id)
                ->first();

            if ($item) {
                if ($request->quantity + $item->quantity > $item->surplus->quantity) {
                    return $this->failureResponse('Not enough quantity in stock.', 400);
                }
                $item->increment('quantity', $request->quantity);
            } else {
                $item = $cart->items()->create([
                    'surplus_id' => $request->surplus_id,
                    'quantity' => $request->quantity,
                ]);
            }
            DB::commit();
            return $this->successResponse(
                new CartItemResource($item->load('surplus')),
                'Item added to cart successfully.',
                201
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('add item' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }

    }

    public function updateItem(UpdateItemRequest $request, $item)
    {
        try {
            DB::beginTransaction();
            $item = CartItem::find($item);
            if (!$item) {
                return $this->failureResponse('Item not found', 404);
            }
            $item->update(['quantity' => $request->quantity]);
            DB::commit();
            return $this->successResponse(new CartItemResource($item->load('surplus')), 'Item updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('update item' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

    public function removeItem($item)
    {
        try {
            DB::beginTransaction();
            $item = CartItem::find($item);
            if (!$item) {
                return $this->failureResponse('Item not found', 404);
            }
            $item->delete();
            DB::commit();
            return $this->successResponse([], 'Item removed successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('remove item' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }

    }

    public function mergeCart(MergeCartRequest $request)
    {
        try {
            DB::beginTransaction();
            $guestCart = Cart::where('session_id', $request->session_id)->first();
            $userCart = Cart::firstOrCreate(['user_id' => auth()->id()]);

            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $userCart->items()->updateOrCreate(
                        ['surplus_id' => $item->surplus_id],
                        ['quantity' => DB::raw("quantity + {$item->quantity}")]
                    );
                }

                $guestCart->delete();
            }
            DB::commit();
            return $this->successResponse([], 'Cart merged successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('merge cart' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }

    }

}
