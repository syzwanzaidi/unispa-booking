<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; 

class ApiAuthController extends Controller

{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email', 
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = Auth::user(); 
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'user' => $user,
            'token' => $token, 
        ], 200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'nullable|string',
            'phone_no' => 'nullable|string|max:20',
        ]);

        $email = $request->email;
        $isMember = 0;
        $uitmDomains = ['@uitm.edu.my', '@student.uitm.edu.my', '@staf.uitm.edu.my'];
        foreach ($uitmDomains as $domain) {
            if (str_ends_with($email, $domain)) {
                $isMember = 1;
                break;
            }
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'phone_no' => $request->phone_no,
                'is_member' => $isMember,
            ]);
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful!',
                'user' => $user,
                'token' => $token,
            ], 201); 
        } catch (\Exception $e) {
            Log::error("User registration failed: " . $e->getMessage());
            return response()->json(['message' => 'Registration failed due to an internal error.'], 500);
        }
    }
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully!'], 200);
    }
}