<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Booking::with('user', 'bookingItems.package');

        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        if ($request->filled('booking_date')) {
            $query->whereDate('booking_date', $request->booking_date);
        }
        if ($request->filled('booking_status') && $request->booking_status != '') {
            $query->where('booking_status', $request->booking_status);
        }

        $bookings = $query->orderBy('booking_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        $oldInput = $request->except('page');

        return view('admin.bookings.index', compact('bookings', 'oldInput'));
    }

    public function show(Booking $booking)
    {
        $booking->load('user', 'bookingItems.package');
        return view('admin.bookings.show', compact('booking'));
    }
    public function edit(Booking $booking)
    {
        $booking->load('user', 'bookingItems.package');
        $packages = Package::all();
        $users = User::all();

        return view('admin.bookings.edit', compact('booking', 'packages', 'users'));
    }
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'booking_status' => 'required|string|in:Pending,Confirmed,Cancelled,Completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $booking->update($request->only([
                'booking_date', 'total_amount', 'payment_method', 'booking_status', 'notes'
            ]));
            if ($request->booking_status === 'Cancelled' && $booking->getOriginal('booking_status') !== 'Cancelled') {
                Log::info("Booking ID: {$booking->booking_id} status changed to Cancelled by Admin.");
            } elseif ($request->booking_status === 'Confirmed' && $booking->getOriginal('booking_status') !== 'Confirmed') {
                 Log::info("Booking ID: {$booking->booking_id} status changed to Confirmed by Admin.");
            }

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update booking (ID: {$booking->booking_id}): " . $e->getMessage());
            return back()->with('error', 'Failed to update booking. Please try again.')->withInput();
        }
    }
    public function cancel(Booking $booking)
    {
        if ($booking->booking_status === 'Cancelled') {
            return back()->with('error', 'Booking is already cancelled.');
        }

        try {
            DB::beginTransaction();

            $booking->booking_status = 'Cancelled';
            $booking->save();

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success', "Booking ID {$booking->booking_id} has been cancelled.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Admin failed to cancel booking (ID: {$booking->booking_id}): " . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }
}
