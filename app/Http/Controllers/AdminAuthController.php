<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Hardcoded admin credentials
        $adminEmail = 'prizekisho@me.com';
        $adminPassword = 'kisho321';

        if ($email === $adminEmail && $password === $adminPassword) {
            return response()->json([
                'success' => true,
                'admin' => [
                    'name' => 'Admin',
                    'email' => $adminEmail
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid admin credentials'
        ]);
    }
}
