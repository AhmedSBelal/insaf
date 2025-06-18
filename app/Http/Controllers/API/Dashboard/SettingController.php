<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function show($id)
    {
        $admin = auth()->user();
        return $admin;
    }

    public function update(Request $request){
        try {
            $admin = auth()->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
                'phone' => 'required|string|max:20',
            ]);

            $admin->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            return response()->json([
                'message' => 'Profile updated successfully',
                'admin' => $admin
            ], 200);

        } catch (\Exception $exception) {
            \Log::error("admin settings update >> \n\n" . $exception->getMessage());
            return response()->json([
                'message' => 'Something went wrong, try again later.'
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $admin = auth()->user();
            
            // dd($request->all());
            $validated = $request->validate([
                'current_password' => 'required|string|min:8',
                'password' => 'required|string|min:8|confirmed',
            ]);
            // dd($validated);
            
            if (!Hash::check($validated['current_password'], $admin->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            $admin->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'message' => 'Password updated successfully'
            ], 200);

        } catch (\Exception $exception) {
            \Log::error("admin password update >> \n\n" . $exception->getMessage());
            return response()->json([
                'message' => 'Something went wrong, try again later.'
            ], 500);
        }
    }

    public function deleteAccount()
    {
        try {
            $admin = auth()->user();
            $admin->delete();

            return response()->json([
                'message' => 'Account deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            \Log::error("admin account deletion >> \n\n" . $exception->getMessage());
            return response()->json([
                'message' => 'Something went wrong, try again later.'
            ], 500);
        }
    }
}
