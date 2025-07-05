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

        // Calculate total before discount
        $totalBeforeDiscount = 0;
        foreach ($invoice->booking->bookingItems as $item) {
            $totalBeforeDiscount += $item->item_price * $item->item_pax;
        }

        // Apply discount if the user is a UiTM member
        $discountAmount = 0;
        if ($invoice->booking->user->is_member) {
            $discountAmount = $totalBeforeDiscount * 0.10;
        }

        $totalAfterDiscount = $totalBeforeDiscount - $discountAmount;

        return view('admin.invoices.show', compact('invoice', 'totalBeforeDiscount', 'discountAmount', 'totalAfterDiscount'));
    }
}
