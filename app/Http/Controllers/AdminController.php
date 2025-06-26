<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $admins = Admin::all();
        return view('admins.index', compact('admins'));
    }
    public function create()
    {
        return view('admins.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'admin_username' => 'required|string|max:255|unique:admins',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'admin_username' => $request->admin_username,
            'admin_password' => Hash::make($request->admin_password),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully!');
    }
    public function show(Admin $admin)
    {
        return view('admins.show', compact('admin'));
    }
    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'admin_username' => 'required|string|max:255|unique:admins,admin_username,' . $admin->admin_id . ',admin_id',
            'admin_password' => 'nullable|string|min:8|confirmed', // Password optional on update
        ]);

        $admin->update([
            'admin_username' => $request->admin_username,
            'admin_password' => $request->admin_password ? Hash::make($request->admin_password) : $admin->admin_password,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully!');
    }
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully!');
    }
}
