<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)// Admin login method
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);// Validate the request data

        // Find admin user from DB
        $admin = User::where('email', $request->email)
                     ->where('is_admin', true) // Ensure the user is an admin
                     ->first();// Retrieve the admin user by email

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,// Invalid credentials
                'message' => 'Invalid admin credentials'
            ], 401);// Return 401 Unauthorized if admin not found or password does not match
        }//

        // Create Sanctum token
        $token = $admin->createToken('admin-token')->plainTextToken;// Generate a token for the admin user

        return response()->json([
            'success' => true,
            'admin' => $admin,
            'token' => $token
        ]);// Return success response with admin details and token
    }// Admin login method
}
