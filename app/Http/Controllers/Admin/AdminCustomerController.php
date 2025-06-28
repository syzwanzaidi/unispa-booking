<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin'); // Ensure only authenticated admins can access
    }

    /**
     * Display a listing of all customers.
     */
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
        $customers = $query->orderBy('created_at', 'desc')->paginate(10); // Paginate for large lists

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer) // Using route model binding
    {
        // Eager load relationships if needed, e.g., bookings, invoices for a customer
        $customer->load('bookings.invoice', 'bookings.bookingItems.package'); // Example of loading customer's related data

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id), // Email unique except for current user
            ],
            'gender' => 'required|in:male,female,other', // Adjust if your genders are different
            'phone_no' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // Optional password change
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

    /**
     * Remove the specified customer from storage.
     * Use with extreme caution as this will delete all associated data (bookings, invoices, payments)!
     */
    public function destroy(User $customer)
    {
        try {
            $customer->delete(); // Due to onDelete('cascade') in migrations, related records might also be deleted
            return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }
}
