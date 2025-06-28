<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::whereHas('invoice.booking', function ($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })
                            ->with('invoice.booking')
                            ->orderBy('payment_date', 'desc')
                            ->get();

        return view('payments.index', compact('payments'));
    }
    public function show(Payment $payment)
    {
        $user = Auth::user();
        $payment->load('invoice.booking.user');

        if ($payment->invoice->booking->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('payments.show', compact('payment'));
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
            return redirect()->back()->with('error', 'Invalid invoice or unauthorized action.');
        }
        if ($invoice->payment()->exists() || $invoice->payment_status === 'Paid') {
            return redirect()->back()->with('error', 'Invoice is already paid or a payment record already exists.');
        }
        if ($request->amount != $invoice->total_price) {
            return redirect()->back()->with('error', 'Payment amount does not match invoice total.');
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

            return redirect()->route('payments.show', $payment->payment_id)->with('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            Log::error("Failed to process payment for Invoice {$invoice->invoice_number}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to process payment. Please try again.');
        }
    }
    public function create() { abort(404, 'Not Found'); }
    public function edit(Payment $payment) { abort(404, 'Not Found'); }
    public function update(Request $request, Payment $payment) { abort(404, 'Not Found'); }
    public function destroy(Payment $payment) { abort(404, 'Not Found'); }
}
