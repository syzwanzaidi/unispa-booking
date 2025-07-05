<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $invoices = Invoice::whereHas('booking', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })
                            ->with('booking')
                            ->orderBy('generated_at', 'desc')
                            ->get();

        return response()->json(['invoices' => $invoices]);
    }

    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        $invoice->loadMissing('booking');

        if (!$invoice->booking || $invoice->booking->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $invoice->load('booking.user', 'booking.bookingItems.package');

        return response()->json(['invoice' => $invoice]);
    }

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
