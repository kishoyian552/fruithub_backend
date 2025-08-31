<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Product::all());
    }// Display a listing of all products

    /**
     * Display the specified product by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);// If product not found, return 404
        }

        return response()->json($product);// If product found, return product details
    }// Display the specified product by ID

    /**
     * Store a newly created product in the database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|url',
            'category' => 'required|string',
            'inStock' => 'required|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews' => 'nullable|integer|min:0',
        ]);// Validate the fields sent by frontend

        $product = Product::create($validated);

        return response()->json($product, 201);
    }// Store a newly created product in the database

    /**
     * Update the specified product by ID.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);// Find the product by ID

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }// If product not found, return 404

        // Validate fields sent by frontend (partial updates allowed)
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|url',
            'category' => 'sometimes|string',
            'inStock' => 'sometimes|boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews' => 'nullable|integer|min:0',
        ]);// Validate the fields sent by frontend

        $product->update($validated);

        return response()->json($product);
    }// Update the specified product by ID

    /**
     * Delete the specified product by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }// If product not found, return 404

        $product->delete();// Delete the product

        return response()->json(['message' => 'Product deleted successfully']);
    }// Delete the specified product by ID
}
