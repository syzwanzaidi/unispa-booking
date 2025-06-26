<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('package')
                            ->orderBy('booking_date', 'desc')
                            ->orderBy('booking_time', 'desc')
                            ->get();

        return view('bookings.index', compact('bookings'));
    }
    public function create(Request $request)
    {
        $users = User::all();
        $packages = Package::all();
        $selectedPackageId = $request->query('package_id');

        return view('bookings.create', compact('users', 'packages', 'selectedPackageId'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,package_id',
            'booking_pax' => 'required|integer|min:1',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'payment_method' => 'required|string',
        ]);

        $package = Package::find($request->package_id);
        if (!$package) {
            return back()->with('error', 'Selected package not found.')->withInput();
        }

        $bookedPax = Booking::where('package_id', $package->package_id)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time)
            ->sum('booking_pax');

        $remainingCapacity = $package->capacity - $bookedPax;

        if ($request->booking_pax > $remainingCapacity) {
            return back()->with('error', 'Sorry, there are only ' . $remainingCapacity . ' slots remaining for this package at the selected time. Please choose a different time or reduce your booking quantity.')->withInput();
        }

        Booking::create([
            'user_id' => Auth::id(),
            'package_id' => $request->package_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'booking_status' => 'Pending',
            'booking_pax' => $request->booking_pax,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }
    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }
    public function edit(Booking $booking)
    {
        $users = User::all();
        $packages = Package::all();
        return view('bookings.edit', compact('booking', 'users', 'packages'));
    }
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_pax' => 'required|integer|min:1',
            'booking_time' => 'required|date_format:H:i',
            'booking_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|string|max:50',
            'package_id' => 'required|exists:packages,package_id',
            'user_id' => 'required|exists:users,user_id',
        ]);

        $booking->update([
            'booking_pax' => $request->booking_pax,
            'booking_time' => $request->booking_time,
            'booking_date' => $request->booking_date,
            'payment_method' => $request->payment_method,
            'package_id' => $request->package_id,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }
}
