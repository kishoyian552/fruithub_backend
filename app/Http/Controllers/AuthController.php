<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)// Register a new user
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);// Validate the fields sent by frontend

        // Automatically generate full name
        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];// Combine first and last name into full name
        $validated['password'] = Hash::make($validated['password']);// Hash the password before saving

        try {
            $user = User::create($validated);// Create a new user in the database

            return response()->json([
                'message' => 'Registration Successful!',
                'user' => $user
            ], 201);// Return success response with user details

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Registration failed.',
                'message' => $exception->getMessage()// Return error message
            ], 500);// Return 500 Internal Server Error if registration fails
        }// Register a new user
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);// Validate the login credentials

        try {
            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);// If user not found or password does not match, throw validation exception
            }

            $token = $user->createToken('auth-token')->plainTextToken;// Create a new token for the user

            return response()->json([
                'message' => 'Login Successful!',
                'user' => $user,
                'token' => $token
            ]);// Return success response with user details and token

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Login failed.',
                'message' => $exception->getMessage()// Return error message
            ], 500);// Return 500 Internal Server Error if login fails
        }
    }// Log in the user

    public function logout(Request $request)// Log out the user
    {
        $request->user()->currentAccessToken()->delete();// Delete the current access token

        return response()->json([
            'message' => 'Log Out Successful.'
        ]);// Log out the user by deleting the current access token
    }
}
