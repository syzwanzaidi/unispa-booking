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
    public function __construct() { $this->middleware('auth'); }
    public function show(Invoice $invoice)
    {
        if ($invoice->booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $invoice->load('booking.user', 'booking.bookingItems.package');
        $booking = $invoice->booking;

        return view('invoices.show', compact('invoice', 'booking'));
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
