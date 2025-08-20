<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find admin user from DB
        $admin = User::where('email', $request->email)
                     ->where('is_admin', true) // Make sure you have an is_admin column
                     ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin credentials'
            ], 401);
        }

        // Create Sanctum token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'admin' => $admin,
            'token' => $token
        ]);
    }
}
