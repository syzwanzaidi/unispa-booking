<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If a user (web guard) is logged in, redirect to user dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        // If an admin (admin guard) is logged in, redirect to admin dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }
    public function login(Request $request)
    {
        // Validate common credentials (identifier and password)
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');
        if (Auth::guard('web')->attempt(['email' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        if (Auth::guard('admin')->attempt(['admin_username' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard')); // Redirect to admin dashboard
        }
        throw ValidationException::withMessages([
            'identifier' => [trans('auth.failed')],
        ]);
    }
    public function showRegistrationForm()
    {
        if (Auth::guard('web')->check() || Auth::guard('admin')->check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    //registration with verification
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

        //Check if email is UiTM community
        $uitmDomains = ['@uitm.edu.my', '@student.uitm.edu.my', '@staf.uitm.edu.my'];
        $isMember = 0;

        foreach ($uitmDomains as $domain) {
            if (str_ends_with($email, $domain)) {
                $isMember = 1;
                break;
            }
        }

        //Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'is_member' => $isMember,
        ]);

        //Send email verification link
        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registration successful! Please verify your email before logging in.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $redirectRoute = route('login');
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $redirectRoute = route('login');
        } else {
            $redirectRoute = route('login');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to($redirectRoute);
    }
}
