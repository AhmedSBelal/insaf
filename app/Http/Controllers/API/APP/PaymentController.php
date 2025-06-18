<?php

namespace App\Http\Controllers\API\APP;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function createStripeIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($request->order_id);

        if ($order->payment_status !== PaymentStatus::Pending) {
            return $this->failureResponse('Payment already processed or not allowed.', 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::create([
                'amount' => $order->total_price * 100, // Stripe uses cents
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            return $this->successResponse([
                'client_secret' => $intent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Intent Error: ' . $e->getMessage());
            return $this->failureResponse('Failed to create payment intent.', 500);
        }
    }
}
