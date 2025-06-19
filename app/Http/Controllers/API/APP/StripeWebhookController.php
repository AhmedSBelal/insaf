<?php

namespace App\Http\Controllers\API\APP;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook error: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            $orderId = $intent->metadata->order_id ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->payment_status === PaymentStatus::Pending) {
                    $order->update([
                        'payment_status' => PaymentStatus::Paid,
                        'status' => OrderStatus::Confirmed->value,
                    ]);

                    $order->payment->update([
                        'status' => PaymentStatus::Paid,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
