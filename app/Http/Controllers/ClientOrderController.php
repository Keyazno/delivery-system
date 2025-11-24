<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\Order;
use App\Services\IdGeneratorService;


class ClientOrderController extends Controller
{
    // // List orders for logged-in client
    protected $idGenerator;
    public function __construct(IdGeneratorService $idGenerator)
{
    $this->idGenerator = $idGenerator;
}


    public function index(Request $request)
    {
        $orders = Order::where('client_id', $request->user()->id)->get();
        return response()->json($orders);
    }

    // Create a new order
    public function store(Request $request)
    {
        $request->validate([
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $order = Order::create([
            'client_id' => $request->user()->id,
            'pickup_address' => $request->pickup_address,
            'destination_address' => $request->destination_address,
            'price' => $request->price,
            'tracking_number' => $this->idGenerator->generateOrderId(),
        ]);

        return response()->json($order, 201);
    }

    // View a single order
    public function show($id, Request $request)
    {
        $order = Order::where('client_id', $request->user()->id)->findOrFail($id);
        return response()->json($order);
    }
}