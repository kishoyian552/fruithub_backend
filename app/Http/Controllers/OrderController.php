<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //Place a new order (after payment). Supports multiple cart items.
     
      
     
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'items'              => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id', 
            'items.*.price'      => 'required|numeric',
            'items.*.quantity'   => 'required|integer|min:1',
            'mpesa_receipt'      => 'nullable|string',
        ]);// Validate the incoming request

        $userId = Auth::check() ? Auth::id() : ($request->user_id ?? null);// Get the user ID from the authenticated user or the provided user ID

        $orders = [];// Initialize an array to hold the created orders
        foreach ($request->items as $item) {
            $orders[] = Order::create([
                'user_id'       => $userId,
                'product_id'    => $item['product_id'], 
                'amount'        => $item['price'] * $item['quantity'],
                'status'        => 'paid',
                'mpesa_receipt' => $request->mpesa_receipt ?? null,
            ]);// Create a new order for each item in the request
        }

        return response()->json([
            'message' => 'Orders placed successfully',
            'orders'  => $orders,
        ], 201);// Return a success response with the created orders
    }

  
     // View all orders by admin only
     
    public function index()// View all orders by admin only
    {
        $user = Auth::user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);// If the user is not logged in or not an admin, return an unauthorized response
        }

        $orders = Order::with('user', 'product')->latest()->get();// Retrieve all orders with related user and product information, ordered
        return response()->json($orders);// Return the orders as a JSON response
    }

    
     //View loggedin user orders.
  
    public function myOrders()//View loggedin user orders.
    {
        $orders = Order::with('product')// Eager load the product relationship
            ->where('user_id', Auth::id())// Get orders for the authenticated user
            ->latest()
            ->get();

        return response()->json($orders);
    }// Return the user's orders as a JSON response
}
