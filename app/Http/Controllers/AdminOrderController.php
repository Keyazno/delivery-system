<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Order;
use App\Services\IdGeneratorService;

class AdminOrderController extends Controller
{
    //
    protected $idGenerator;
    public function __construct(IdGeneratorService $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function index()
    {
        return response()->json(Order::all());
    }
    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->driver_id = $request->driver_id;
        $order->status = 'in_transit';
        $order->save();

        return response()->json($order);
    }
    public function cancel(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = 'canceled';
        $order->save();

        return response()->json($order);
    }
}
