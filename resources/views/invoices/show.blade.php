@extends('layout.layout')

@section('content')
<div class="container mt-5">
    @isset($invoices)
        <h1 class="mb-4">My Invoices</h1>

        @if ($invoices->isEmpty())
            <div class="alert alert-info">
                You currently have no invoices.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Booking ID</th>
                            <th>Total Price</th>
                            <th>Generated At</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoiceItem)
                            <tr>
                                <td>{{ $invoiceItem->invoice_number }}</td>
                                <td>{{ $invoiceItem->booking_id }}</td>
                                <td>RM {{ number_format($invoiceItem->total_price, 2) }}</td>
                                <td>{{ $invoiceItem->generated_at ? \Carbon\Carbon::parse($invoiceItem->generated_at)->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    @if ($invoiceItem->payment_status == 'Paid')
                                        <span class="badge bg-success">{{ $invoiceItem->payment_status }}</span>
                                    @elseif ($invoiceItem->payment_status == 'Pending')
                                        <span class="badge bg-warning">{{ $invoiceItem->payment_status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $invoiceItem->payment_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoiceItem->invoice_id) }}" class="btn btn-sm btn-info">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endisset
    @isset($invoice)
        @if (!isset($invoices))
        <h1 class="mb-4">Invoice #{{ $invoice->invoice_number }}</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Invoice Details
            </div>
            <div class="card-body">
                <p><strong>Customer:</strong> 
                    {{ $invoice->booking->user->name ?? 'N/A' }}
                    @if($invoice->booking->user->is_member)
                        <span class="badge bg-success ms-2">UiTM Member</span>
                    @endif
                </p>

                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice->generated_at ? \Carbon\Carbon::parse($invoice->generated_at)->format('Y-m-d H:i:s') : 'N/A' }}</p>

                <p><strong>Total Before Discount:</strong> RM {{ number_format($totalBeforeDiscount, 2) }}</p>

                @if ($discountAmount > 0)
                    <p><strong>UiTM Member Discount (10%):</strong> -RM {{ number_format($discountAmount, 2) }}</p>
                    <p><strong>Total After Discount:</strong> <span class="text-success fw-bold">RM {{ number_format($totalAfterDiscount, 2) }}</span></p>
                @else
                    <p><strong>Total After Discount:</strong> <span class="text-muted">No discount applied</span></p>
                @endif

                <p><strong>Payment Status:</strong>
                    @if ($invoice->payment_status == 'Paid')
                        <span class="badge bg-success">{{ $invoice->payment_status }}</span>
                    @elseif ($invoice->payment_status == 'Pending')
                        <span class="badge bg-warning">{{ $invoice->payment_status }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $invoice->payment_status }}</span>
                    @endif
                </p>
            </div>
        </div>

        @if ($invoice->booking)
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    Booking Details (Booking ID: {{ $invoice->booking->booking_id }})
                </div>
                <div class="card-body">
                    <p><strong>Customer:</strong> {{ $invoice->booking->user->name ?? 'N/A' }}</p>
                    <p><strong>Booking Date:</strong> {{ $invoice->booking->booking_date ? \Carbon\Carbon::parse($invoice->booking->booking_date)->format('Y-m-d') : 'N/A' }}</p>
                    <p><strong>Booking Status:</strong> {{ $invoice->booking->booking_status }}</p>
                    <p><strong>Payment Method:</strong> {{ $invoice->booking->payment_method }}</p>

                    @if ($invoice->booking->bookingItems->isNotEmpty())
                        <h6 class="mt-3">Booked Items:</h6>
                        <ul class="list-group list-group-flush">
                            @foreach ($invoice->booking->bookingItems as $item)
                                <li class="list-group-item">
                                    {{ $item->package->package_name ?? 'N/A' }} ({{ $item->quantity }} x RM {{ number_format($item->item_price, 2) }})
                                    {{-- Add item_pax here --}}
                                    @if ($item->item_pax)
                                        <br><small>Pax: {{ $item->item_pax }}</small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to All Invoices</a>
        <button class="btn btn-success" onclick="window.print()">Download/Print Invoice</button>

        @endif
    @endisset

</div>
@endsection
