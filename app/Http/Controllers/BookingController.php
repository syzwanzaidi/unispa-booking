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
        if (Auth::check()) {
            $bookings = Auth::user()->bookings;
        } else {
            $bookings = Booking::all(); // For admin view, show all bookings
        }
        return view('bookings.index', compact('bookings'));
    }
    public function create()
    {
        $users = User::all();
        $packages = Package::all();
        return view('bookings.create', compact('users', 'packages'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'booking_pax' => 'required|integer|min:1',
            'booking_time' => 'required|date_format:H:i',
            'booking_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|string|max:50',
            'package_id' => 'required|exists:packages,package_id',
            'user_id' => 'required|exists:users,user_id',
        ]);

        Booking::create([
            'booking_pax' => $request->booking_pax,
            'booking_time' => $request->booking_time,
            'booking_date' => $request->booking_date,
            'payment_method' => $request->payment_method,
            'package_id' => $request->package_id,
            'user_id' => $request->user_id ?? Auth::id(),
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
