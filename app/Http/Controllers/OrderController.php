<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Place a new order (after payment).
     * Supports multiple cart items.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'items'              => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id', // <-- use product_id
            'items.*.price'      => 'required|numeric',
            'items.*.quantity'   => 'required|integer|min:1',
            'mpesa_receipt'      => 'nullable|string',
        ]);

        $userId = Auth::check() ? Auth::id() : ($request->user_id ?? null);

        $orders = [];
        foreach ($request->items as $item) {
            $orders[] = Order::create([
                'user_id'       => $userId,
                'product_id'    => $item['product_id'], // <-- match validation
                'amount'        => $item['price'] * $item['quantity'],
                'status'        => 'paid',
                'mpesa_receipt' => $request->mpesa_receipt ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Orders placed successfully',
            'orders'  => $orders,
        ], 201);
    }

    /**
     * View all orders (Admin only).
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orders = Order::with('user', 'product')->latest()->get();
        return response()->json($orders);
    }

    /**
     * View logged-in user orders.
     */
    public function myOrders()
    {
        $orders = Order::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($orders);
    }
}
