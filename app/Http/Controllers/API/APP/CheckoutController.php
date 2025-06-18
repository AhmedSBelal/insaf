<?php

namespace App\Http\Controllers\API\APP;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\APP\Order\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CheckoutController extends Controller
{

    use ApiResponse;

    /**
     * Process checkout from cart
     *
     * @param CheckoutRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(CheckoutRequest $request)
    {
        // Get authenticated user's cart
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->failureResponse('Cart is empty', 400);
        }

        // Check if all items are from the same supplier
        $supplierIds = $cart->items->pluck('surplus.supplier_id')->unique();
        if ($supplierIds->count() > 1) {
            return $this->failureResponse('All items must be from the same supplier', 400);
        }

        $supplierId = $supplierIds->first();
        $charity = $user->charity;

        if (!$charity) {
            return $this->failureResponse('User is not associated with a charity', 400);
        }

        try {
            // Start transaction
            return DB::transaction(function () use ($cart, $charity, $supplierId, $request) {
                // Calculate total price
                $totalPrice = 0;
                foreach ($cart->items as $item) {
                    $totalPrice += $item->surplus->price * $item->quantity;
                }

                // Create payment
                $payment = Payment::create([
                    'amount' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'status' => PaymentStatus::Pending,
                ]);

                // Create order
                $order = Order::create([
                    'charity_id' => $charity->id,
                    'supplier_id' => $supplierId,
                    'payment_id' => $payment->id,
                    'payment_status' => PaymentStatus::Pending,
                    'total_price' => $totalPrice,
                    'status' => OrderStatus::Pending,
                ]);

                // Add items to order
                foreach ($cart->items as $item) {
                    $surplus = $item->surplus;

                    // Check if enough quantity is available
                    if ($surplus->quantity < $item->quantity) {
                        throw new \Exception("Not enough quantity available for {$surplus->name}");
                    }

                    // Add to order
                    $order->surpluses()->attach($surplus->id, [
                        'quantity' => $item->quantity,
                        'price' => $surplus->price,
                        'surplus_name' => $surplus->name,
                    ]);

                    // Deduct quantity from surplus
                    $surplus->quantity -= $item->quantity;
                    $surplus->save();
                }

                // Empty the cart
                $cart->items()->delete();

                // Return success response with order details
                return $this->successResponse([
                    'order' => [
                        'id' => $order->id,
                        'total_price' => $order->total_price,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                    ]
                ], 'Order created successfully', 201
                );
            });
        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage());;
            return $this->failureResponse('Something went wrong, please try again later.', 500);
        }
    }
}
