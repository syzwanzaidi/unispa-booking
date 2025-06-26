<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }
    public function create()
    {
        $invoices = Invoice::all();
        return view('payments.create', compact('invoices'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|in:Pending,Completed,Failed',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'invoice_id' => $request->invoice_id,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
    }
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }
    public function edit(Payment $payment)
    {
        $invoices = Invoice::all();
        return view('payments.edit', compact('payment', 'invoices'));
    }
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,invoice_id',
            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|in:Pending,Completed,Failed',
            'payment_date' => 'required|date',
        ]);

        $payment->update([
            'invoice_id' => $request->invoice_id,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }
}
