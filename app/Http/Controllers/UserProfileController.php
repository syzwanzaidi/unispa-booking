<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @var \App\Models\User $user
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate basic profile information
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'gender' => 'required|in:male,female,other',
            'phone_no' => 'required|string|max:20',
        ]);

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender');
        $user->phone_no = $request->input('phone_no');
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
    public function showPasswordChangeForm()
    {
        return view('profile.change-password');
    }
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The provided password does not match your current password.');
                }
            }],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        Auth::guard('web')->login($user);

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully!');
    }
}
