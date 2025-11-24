<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

use Illuminate\Http\Request;
use App\Services\IdGeneratorService;

class DriverOrderController extends Controller
{
    //
    protected $idGenerator;
    public function __construct(IdGeneratorService $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function index(Request $request)
    {
        $orders = Order::where('driver_id', $request->user()->id)->get();
        return response()->json($orders);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_transit,delivered,canceled',
        ]);
        $order = Order::find($id);

        if (!$order || $order->driver_id !== $request->user()->id) {
            return response()->json(['message' => 'Order not found or unauthorized'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json($order);
    }
}