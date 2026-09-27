<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index()
{
    $orders = Order::all();

    return response()->json([
        'orders' => $orders,
    ]);
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'reference' => ['required', 'string', 'max:255'],
        'status' => ['nullable', 'string', 'max:50'],
    ]);

    $order = Order::create([
        'reference' => $validated['reference'],
        'status' => $validated['status'] ?? 'pending',
    ]);

    return response()->json([
        'message' => 'Order created successfully.',
        'order' => $order,
    ], 201);
}
}