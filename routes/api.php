<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\OrderController;



// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);// Login

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);// Logout
});

// Products routes
Route::get('/products', [ProductController::class, 'index']);// Get all products
Route::get('/products/{id}', [ProductController::class, 'show']);// Get product by ID
Route::post('/products', [ProductController::class, 'store']);// Create product
Route::put('/products/{id}', [ProductController::class, 'update']);// Update product
Route::delete('/products/{id}', [ProductController::class, 'destroy']);// Delete product

// Mpesa Routes
Route::post('/mpesa/stkpush', [MpesaController::class, 'stkPush']);// Mpesa stk push
Route::post('/mpesa/callback', [MpesaController::class, 'mpesaCallback']);// Mpesa callback

// Admin login
Route::post('/admin/login', [AdminAuthController::class, 'login']);// Admin login

// User management routes (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);// Get all users
    Route::get('/users/{id}', [UserController::class, 'show']);// Get user by ID
    Route::put('/users/{id}', [UserController::class, 'update']);// Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']);// Delete user

    // Orders routes (new)
    Route::get('/orders', [OrderController::class, 'index']);      // list all orders (admin only)
    Route::get('/orders/{id}', [OrderController::class, 'show']);  // view single order
    Route::post('/orders', [OrderController::class, 'store']);     // create new order (after payment)
    Route::put('/orders/{id}', [OrderController::class, 'update']); // update order status
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']); // delete order
});
