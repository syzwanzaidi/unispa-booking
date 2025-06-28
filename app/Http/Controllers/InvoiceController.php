<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web'); // Ensure it's for 'web' guard users
    }

    /**
     * Display a single invoice or a list of invoices for the authenticated user.
     *
     * @param  \App\Models\Invoice|null  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(?Invoice $invoice = null) // Make the Invoice parameter nullable
    {
        // Ensure the authenticated user is a 'web' guard user (customer)
        if (!Auth::guard('web')->check()) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard')->with('error', 'Admins do not view customer invoices this way.');
            }
            return redirect('/login')->with('error', 'Please log in to view your invoices.');
        }

        $user = Auth::user();

        if ($invoice) {
            // This block handles showing a SINGLE specific invoice
            // Ensure the logged-in user owns this invoice through their booking
            $invoice->loadMissing('booking');

            if (!$invoice->booking || $invoice->booking->user_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            }

            // Eager load related data for the show view (booking, bookingItems, package)
            $invoice->load('booking.user', 'booking.bookingItems.package');
            $booking = $invoice->booking; // For convenience if you use $booking in view

            return view('invoices.show', compact('invoice', 'booking'));

        } else {
            // This block handles showing the LIST of all invoices for the user
            $invoices = Invoice::whereHas('booking', function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                })
                                ->with('booking') // Eager load the booking relationship
                                ->orderBy('generated_at', 'desc')
                                ->get();

            // Pass a collection of invoices to the 'show' view.
            // The 'show' view will then need to iterate through this collection.
            return view('invoices.show', compact('invoices'));
        }
    }

    // Your generateInvoiceForBooking method remains the same
    public function generateInvoiceForBooking($bookingId)
    {
        $booking = Booking::with('invoice')->find($bookingId);
        if (!$booking) {
            Log::error("Booking ID {$bookingId} not found for invoice generation.");
            return null;
        }

        if ($booking->invoice) {
            return $booking->invoice;
        }

        try {
            $invoiceNumber = 'INV-' . Carbon::now()->format('Ymd') . '-' . str_pad($booking->booking_id, 5, '0', STR_PAD_LEFT);
            $initialPaymentStatus = 'Pending';
            if ($booking->payment_method === 'Online' && $booking->booking_status === 'Confirmed') {
                $initialPaymentStatus = 'Paid';
            }

            $invoice = Invoice::create([
                'booking_id' => $booking->booking_id,
                'invoice_number' => $invoiceNumber,
                'total_price' => $booking->total_amount,
                'generated_at' => now(),
                'payment_status' => $initialPaymentStatus,
            ]);
            Log::info("New invoice generated: " . $invoice->invoice_number . " for Booking ID: " . $booking->booking_id);
            return $invoice;

        } catch (\Exception $e) {
            Log::error("Failed to generate invoice for Booking ID {$booking->booking_id}: " . $e->getMessage());
            return null;
        }
    }
}
