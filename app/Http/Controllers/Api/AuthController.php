<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
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
        $uitmDomains = ['@uitm.edu.my', '@student.uitm.edu.my', '@staf.uitm.edu.my'];
        $isMember = 0;

        foreach ($uitmDomains as $domain) {
            if (str_ends_with($email, $domain)) {
                $isMember = 1;
                break;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'is_member' => $isMember,
        ]);

        event(new Registered($user));

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful! Please verify your email before logging in.',
            'user' => $user->only(['id', 'name', 'email', 'is_member']),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully!',
            'user' => $user->only(['id', 'name', 'email', 'is_member']),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully!']);
    }
}
