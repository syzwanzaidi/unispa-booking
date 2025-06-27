<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    private function generateTimeSlots($intervalMinutes = 30, $startHour = 10, $endHour = 19)
    {
        $slots = [];
        $current = Carbon::createFromTime($startHour, 0, 0);
        $end = Carbon::createFromTime($endHour, 0, 0);

        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($intervalMinutes);
        }
        return $slots;
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('bookingItems.package')
                            ->orderBy('booking_date', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('bookings.index', compact('bookings'));
    }
    public function create(Request $request)
    {
        $users = User::all();
        $packages = Package::all();
        $selectedPackageId = $request->query('package_id');

        $timeSlots = $this->generateTimeSlots(30, 10, 19);

        return view('bookings.create', compact('users', 'packages', 'selectedPackageId', 'timeSlots'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.package_id' => 'required|exists:packages,package_id',
            'items.*.item_pax' => 'required|integer|min:1',
            'items.*.item_start_time' => 'required|date_format:H:i',
            'items.*.for_whom_name' => 'nullable|string|max:255',
        ]);

        $bookingDate = $request->booking_date;
        $totalBookingAmount = 0;
        DB::beginTransaction();

        try {
            foreach ($request->input('items') as $index => $itemData) {
                $package = Package::find($itemData['package_id']);
                if (!$package) {
                    DB::rollBack();
                    return back()->with('error', 'Selected package for item ' . ($index + 1) . ' not found.')->withInput();
                }
                $durationInMinutes = 0;
                if (str_contains($package->duration, 'Minutes')) {
                    $durationInMinutes = (int) filter_var($package->duration, FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $durationInMinutes = 30;
                }

                $itemStartTime = Carbon::parse($itemData['item_start_time']);
                $itemEndTime = $itemStartTime->copy()->addMinutes($durationInMinutes);
                $bookedPaxAtOverlap = 0;
                $existingBookingItems = BookingItem::whereHas('booking', function ($query) use ($bookingDate) {
                        $query->where('booking_date', $bookingDate);
                    })
                    ->where('package_id', $package->package_id)
                    ->get();

                foreach ($existingBookingItems as $existingItem) {
                    $existingItemDurationMinutes = $existingItem->item_duration_minutes;
                    $existingItemStart = Carbon::parse($existingItem->item_start_time);
                    $existingItemEnd = $existingItemStart->copy()->addMinutes($existingItemDurationMinutes);
                    if ($itemStartTime->lt($existingItemEnd) && $itemEndTime->gt($existingItemStart)) {
                        $bookedPaxAtOverlap += $existingItem->item_pax;
                    }
                }
                if (($itemData['item_pax'] + $bookedPaxAtOverlap) > $package->capacity) {
                    DB::rollBack();
                    return back()->with('error', 'Sorry, not enough capacity for ' . $package->package_name . ' at ' . $itemData['item_start_time'] . ' (Item ' . ($index + 1) . '). Please choose a different time or reduce quantity.')
                                 ->withInput();
                }
                $totalBookingAmount += ($package->package_price * $itemData['item_pax']);
            }
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_date' => $bookingDate,
                'booking_status' => 'Pending',
                'payment_method' => $request->payment_method,
                'total_amount' => $totalBookingAmount,
                'notes' => $request->notes,
            ]);
            foreach ($request->input('items') as $itemData) {
                $package = Package::find($itemData['package_id']);

                $durationInMinutes = 0;
                if (str_contains($package->duration, 'Minutes')) {
                    $durationInMinutes = (int) filter_var($package->duration, FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $durationInMinutes = 30;
                }

                BookingItem::create([
                    'booking_id' => $booking->booking_id,
                    'package_id' => $package->package_id,
                    'item_pax' => $itemData['item_pax'],
                    'item_start_time' => $itemData['item_start_time'],
                    'item_duration_minutes' => $durationInMinutes,
                    'item_price' => $package->package_price,
                    'for_whom_name' => $itemData['for_whom_name'],
                ]);
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking created successfully with all selected packages!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking creation failed: " . $e->getMessage() . " - Trace: " . $e->getTraceAsString());
            return back()->with('error', 'An unexpected error occurred during booking. Please try again. If the problem persists, contact support.')->withInput();
        }
    }
    public function show(Booking $booking)
    {
        // Ensure only the authenticated user can view their bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        // Eager load booking items and their packages for display
        $booking->load('bookingItems.package');
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
