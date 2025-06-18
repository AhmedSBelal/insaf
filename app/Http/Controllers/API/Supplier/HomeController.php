<?php

namespace App\Http\Controllers\API\Supplier;

use App\Enums\OrderStatus;
use App\Http\Controllers\APIBaseController;
use App\Http\Resources\API\Supplier\NotificationsResource;
use App\Http\Resources\API\Supplier\OrdersResource;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends APIBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $supplier = $request->user()->supplier;

        $orderCount = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->distinct()->count('orders.id');

        $charitiesCount = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->distinct()->count('charity_id');

        $completedOrdersCount = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where('status', OrderStatus::Completed->value)->distinct()->count('orders.id');

        $pendingOrdersCount = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where('status', OrderStatus::Pending->value)->distinct()->count('orders.id');

        $recentOrders = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->with(['charity', 'surpluses'])
        ->get();

        // dd($request->user()->notifications()->get());
        $recentNotifications = $request->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return $this->successResponse(
            [
                'order_count' => $orderCount,
                'charities_count' => $charitiesCount,
                'completed_orders_count' => $completedOrdersCount,
                'pending_orders_count' => $pendingOrdersCount,
                'recent_orders' => OrdersResource::collection($recentOrders),
                'recent_notifications' => NotificationsResource::collection($recentNotifications)
            ],
            'Home data retrieved successfully.'
        );
    }
}
