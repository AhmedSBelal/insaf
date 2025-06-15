<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\APIBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\Supplier\OrdersResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends APIBaseController
{
    public function index(Request $request)
    {
//        $supplier = $request->user()->supplier;

        $orders = Order::query()
            ->when($request->has('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->has('date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->input('date'));
            })
            ->paginate(10);

//        dd($orders);

        return $this->successResponse(
            OrdersResource::collection($orders)->response()->getData(true),
            'Orders retrieved successfully.'
        );
    }


    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);

            return $this->successResponse(
                new OrdersResource($order),
                'Order retrieved successfully.'
            );
        } catch (\Exception $exception) {
            return $this->failureResponse('Order not found', 404);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return $this->successResponse(
                [],
                'Order deleted successfully.'
            );
        } catch (\Exception $exception) {
            return $this->failureResponse('Order not found', 404);
        }
    }

}
