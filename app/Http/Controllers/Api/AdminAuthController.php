<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'admin_username' => 'required|string',
            'admin_password' => 'required|string',
        ]);

        $credentials = [
            'admin_username' => $request->admin_username,
            'password' => $request->admin_password,
        ];

        if (!Auth::guard('admin')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'admin_username' => ['The provided admin credentials are incorrect.'],
            ]);
        }

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('adminToken')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful!',
            'admin' => $admin,
            'token' => $token,
        ]);
    }
}
