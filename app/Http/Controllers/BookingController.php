<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Package;
use App\Models\User;
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

                // --- MODIFICATION START ---
                $existingBookingItems = BookingItem::whereHas('booking', function ($query) use ($bookingDate) {
                        $query->where('booking_date', $bookingDate)
                              ->where('booking_status', '!=', 'Cancelled'); // Exclude cancelled bookings
                    })
                    ->where('package_id', $package->package_id)
                    ->get();
                // --- MODIFICATION END ---

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
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $booking->load('bookingItems.package');
        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->booking_status === 'Cancelled' || $booking->booking_status === 'Completed') {
            return back()->with('error', 'This booking cannot be cancelled as it is already ' . $booking->booking_status . '.');
        }

        try {
            $booking->update(['booking_status' => 'Cancelled']);
            return redirect()->route('bookings.index')->with('success', 'Booking (ID: ' . $booking->booking_id . ') has been successfully cancelled.');
        } catch (\Exception $e) {
            Log::error("Booking cancellation failed for ID: {$booking->booking_id} - " . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again or contact support.');
        }
    }

    public function edit(Booking $booking)
    {
        $users = User::all();
        $packages = Package::all();
        $booking->load('bookingItems');
        $timeSlots = $this->generateTimeSlots(30, 10, 19);
        return view('bookings.edit', compact('booking', 'users', 'packages', 'timeSlots'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'nullable|exists:booking_items,id',
            'items.*.package_id' => 'required|exists:packages,package_id',
            'items.*.item_pax' => 'required|integer|min:1',
            'items.*.item_start_time' => 'required|date_format:H:i',
            'items.*.for_whom_name' => 'nullable|string|max:255',
        ]);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $bookingDate = $request->booking_date;

        DB::beginTransaction();
        try {
            $totalBookingAmount = 0;
            $updatedItemIds = [];

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

                // --- MODIFICATION START ---
                $existingBookingItems = BookingItem::whereHas('booking', function ($query) use ($bookingDate) {
                        $query->where('booking_date', $bookingDate)
                              ->where('booking_status', '!=', 'Cancelled'); // Exclude cancelled bookings
                    })
                    ->where('package_id', $package->package_id)
                    ->when(isset($itemData['item_id']), function ($query) use ($itemData) {
                        // Exclude the current item from capacity check if it's being updated
                        $query->where('id', '!=', $itemData['item_id']);
                    })
                    ->get();
                // --- MODIFICATION END ---

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
                    return back()->with('error', 'Capacity error for ' . $package->package_name . ' at ' . $itemData['item_start_time'] . ' (Item ' . ($index + 1) . ').')->withInput();
                }

                if (isset($itemData['item_id'])) {
                    $item = BookingItem::find($itemData['item_id']);
                    if ($item && $item->booking_id === $booking->booking_id) {
                        $item->update([
                            'package_id' => $package->package_id,
                            'item_pax' => $itemData['item_pax'],
                            'item_start_time' => $itemData['item_start_time'],
                            'item_duration_minutes' => $durationInMinutes,
                            'item_price' => $package->package_price,
                            'for_whom_name' => $itemData['for_whom_name'],
                        ]);
                        $updatedItemIds[] = $item->id;
                    } else {
                        DB::rollBack();
                        return back()->with('error', 'Invalid item ID found for item ' . ($index + 1) . '.')->withInput();
                    }
                } else {
                    $newItem = BookingItem::create([
                        'booking_id' => $booking->booking_id,
                        'package_id' => $package->package_id,
                        'item_pax' => $itemData['item_pax'],
                        'item_start_time' => $itemData['item_start_time'],
                        'item_duration_minutes' => $durationInMinutes,
                        'item_price' => $package->package_price,
                        'for_whom_name' => $itemData['for_whom_name'],
                    ]);
                    $updatedItemIds[] = $newItem->id;
                }
                $totalBookingAmount += ($package->package_price * $itemData['item_pax']);
            }

            $booking->bookingItems()->whereNotIn('id', $updatedItemIds)->delete();

            $booking->update([
                'booking_date' => $request->booking_date,
                'payment_method' => $request->payment_method,
                'total_amount' => $totalBookingAmount,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking update failed for ID: {$booking->booking_id} - " . $e->getMessage() . " - Trace: " . $e->getTraceAsString());
            return back()->with('error', 'An unexpected error occurred during booking update. Please try again.')->withInput();
        }
    }
    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }
}
