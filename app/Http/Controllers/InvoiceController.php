<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }
    public function create()
    {
        $bookings = Booking::all();
        return view('invoices.create', compact('bookings'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id|unique:invoices,booking_id',
            'total_price' => 'required|numeric|min:0',
        ]);

        Invoice::create([
            'booking_id' => $request->booking_id,
            'total_price' => $request->total_price,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice generated successfully!');
    }
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }
    public function edit(Invoice $invoice)
    {
        $bookings = Booking::all();
        return view('invoices.edit', compact('invoice', 'bookings'));
    }
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id|unique:invoices,booking_id,' . $invoice->invoice_id . ',invoice_id',
            'total_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'booking_id' => $request->booking_id,
            'total_price' => $request->total_price,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }
}
