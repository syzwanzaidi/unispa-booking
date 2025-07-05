<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::whereHas('invoice.booking', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })
                            ->with('invoice.booking')
                            ->orderBy('payment_date', 'desc')
                            ->get();

        return response()->json(['payments' => $payments]);
    }

    public function show(Payment $payment)
    {
        $user = Auth::user();
        $payment->load('invoice.booking.user');

        if ($payment->invoice->booking->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        return response()->json(['payment' => $payment]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
        ]);

        $invoice = Invoice::find($request->invoice_id);
        if (!$invoice || $invoice->booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Invalid invoice or unauthorized action.'], 403);
        }
        if ($invoice->payment()->exists() || $invoice->payment_status === 'Paid') {
            return response()->json(['error' => 'Invoice is already paid or a payment record already exists.'], 400);
        }
        if ($request->amount != $invoice->total_price) {
            return response()->json(['error' => 'Payment amount does not match invoice total.'], 400);
        }

        try {
            $payment = Payment::create([
                'invoice_id' => $invoice->invoice_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'Completed',
                'payment_date' => now(),
            ]);
            $invoice->payment_status = 'Paid';
            $invoice->save();

            Log::info("Payment ID {$payment->payment_id} for Invoice {$invoice->invoice_number} processed successfully. Invoice status updated to Paid.");

            return response()->json(['message' => 'Payment processed successfully!', 'payment' => $payment]);

        } catch (\Exception $e) {
            Log::error("Failed to process payment for Invoice {$invoice->invoice_number}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to process payment. Please try again.'], 500);
        }
    }
}
