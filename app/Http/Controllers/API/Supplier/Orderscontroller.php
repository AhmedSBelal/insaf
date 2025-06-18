<?php

namespace App\Http\Controllers\API\Supplier;

use App\Enums\OrderStatus;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests\API\Supplier\UpdateOrdersRequest;
use App\Http\Resources\API\Supplier\OrdersResource;
use App\Models\Order;
use Illuminate\Http\Request;

class Orderscontroller extends APIBaseController
{
    public function index(Request $request)
    {
        $supplier = $request->user()->supplier;

        $orders = Order::whereHas('surpluses', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })
        ->when($request->has('status'), function ($query) use ($request) {
            $query->where('status', $request->input('status'));
        })
        ->when($request->has('date'), function ($query) use ($request) {
            $query->whereDate('created_at', $request->input('date'));
        })
        ->paginate(10);

        return $this->successResponse(
            OrdersResource::collection($orders)->response()->getData(true),
            'Orders retrieved successfully.'
        );
    }

    public function updateStatus(UpdateOrdersRequest $request,$id)
    {
        $order = Order::where('id', $id)
            ->whereHas('surpluses', function ($query) use ($request) {
                $query->where('supplier_id', $request->user()->supplier->id);
            })
            ->firstOrFail();

        

        if($order->status !== 'Pending') {
            return $this->failureResponse('Only pending orders can be updated.', 400);
        }

        $order->update([
            'status' => $request->input('status'),
        ]);

        return $this->successResponse(
            new OrdersResource($order),
            'Order status updated successfully.'
        );
    }
}
