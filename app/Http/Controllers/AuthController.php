<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // --- Login Methods ---

    public function showLoginForm()
    {
        // If user is already logged in, redirect them away from login page
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Or your intended home page
        }
        return view('auth.login'); // Create this Blade view
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) { // 'remember' is for "remember me" functionality
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Redirect to intended URL or /dashboard
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Laravel's default failed authentication message
        ]);
    }

    // --- Registration Methods ---

    public function showRegistrationForm()
    {
        // If user is already logged in, redirect them away from register page
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register'); // Create this Blade view
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' means it needs 'password_confirmation' field
            'gender' => 'nullable|string|in:Male,Female,Other', // Validation for ERD field
            'phone_no' => 'nullable|string|max:20', // Validation for ERD field
            'is_member' => 'nullable|boolean', // Validation for ERD field
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // The User model's setPasswordAttribute will hash this
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'is_member' => $request->has('is_member'), // Checkbox handling
        ]);

        Auth::login($user); // Log the user in immediately after registration

        return redirect()->route('dashboard'); // Redirect to dashboard after successful registration
    }

    // --- Logout Method ---

    public function logout(Request $request)
    {
        Auth::logout(); // Logout the user

        $request->session()->invalidate(); // Invalidate the current session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/login'); // Redirect to login page after logout
    }
}
