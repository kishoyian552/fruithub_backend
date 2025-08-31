<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // Get all users
    public function index(): JsonResponse
    {
        return response()->json(User::all());
    }// Get all users

    // Get a single user
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);// Find user by ID

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }// If user not found, return 404

        return response()->json($user);
    }// Get a single user

    // Update user details
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);// Find user by ID

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }// If user not found, return 404

        $validated = $request->validate([
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'email' => 'sometimes|string|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
        ]);// Validate fields sent by frontend 

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }// If password is provided, hash it

        $user->update($validated);// Update user with validated data

        return response()->json($user);// Return updated user details
    }// Update user details

    // Delete a user
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);// Find user by ID

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }// If user not found, return 404

        $user->delete();// Delete the user

        return response()->json(['message' => 'User deleted successfully']);
    }// Delete a user
}
