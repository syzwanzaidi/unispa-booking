<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        $query = User::query();

        // Implement search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone_no', 'like', '%' . $search . '%');
            });
        }

        // Order by latest registered
        $customers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }
    public function show(User $customer) // Using route model binding
    {
        $customer->load('bookings.invoice', 'bookings.bookingItems.package');

        return view('admin.customers.show', compact('customer'));
    }
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'gender' => 'required|in:male,female,other',
            'phone_no' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->gender = $request->gender;
        $customer->phone_no = $request->phone_no;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        return redirect()->route('admin.customers.index')->with('success', 'Customer profile updated successfully!');
    }
    public function destroy(User $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }
}
