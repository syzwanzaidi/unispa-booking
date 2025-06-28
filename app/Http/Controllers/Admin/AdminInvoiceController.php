<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $invoices = Invoice::with('booking.user')
                           ->orderBy('generated_at', 'desc')
                           ->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }
    public function show(Invoice $invoice)
    {
        $invoice->load('booking.user', 'booking.bookingItems.package');

        return view('admin.invoices.show', compact('invoice'));
    }
}
