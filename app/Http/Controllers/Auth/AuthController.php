<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean', // Optional: Add validation for "remember" field
        ]);

        // Manually verify credentials using the User model
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Set token expiration based on "remember me"
            $expiration = $request->boolean('remember') ? now()->addDays(30) : null; // 30 days for "remember me"

            // Generate token with optional expiration
            $token = $user->createToken('auth-token', ['expires_at' => $expiration])->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ]);
        }

        // If authentication fails
        return response()->json(['message' => 'Invalid credentials'], 401);
    }    // Logout method
    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Fetch authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
