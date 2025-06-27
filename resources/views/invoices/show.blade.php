@extends('layout.layout')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card p-4 shadow-sm">
        <div class="row mb-4">
            <div class="col-6">
                <h1 class="mb-0 text-primary">INVOICE</h1>
                <p class="text-muted fs-5">#{{ $invoice->invoice_number }}</p>
            </div>
            <div class="col-6 text-end">
                {{-- <img src="" alt="UniSPA Logo" style="max-height: 80px;"> --}}
                <h4 class="mb-0">UniSPA Booking System</h4>
                <address class="mb-0">
                    Tingkat 3 UNISPA, Universiti Teknologi MARA (UiTM) Shah Alam,<br>
                    UiTM-MTDC Technopreneur Centre, 40450 Shah Alam,<br>
                    Selangor, Malaysia<br>
                    Email: contact@unispabooking.com<br>
                    Phone: 011-1303 7796
                </address>
            </div>
        </div>

        <hr class="my-4">

        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Bill To:</strong><br>
                <strong>{{ $booking->user->name }}</strong><br>
                {{ $booking->user->email }}<br>
                @if($booking->user->phone_no){{ $booking->user->phone_no }}@endif
            </div>
            <div class="col-md-6 text-end">
                <strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($invoice->generated_at)->format('M d, Y') }}<br>
                <strong>Booking Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}<br>
                <strong>Payment Method:</strong> {{ $booking->payment_method }}<br>
                <strong>Payment Status:</strong>
                <span class="badge {{ $invoice->payment_status === 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $invoice->payment_status }}
                </span>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Package Name</th>
                        <th class="text-center">Pax</th>
                        <th class="text-center">Time</th>
                        <th class="text-center">Duration</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $itemNumber = 1; @endphp
                    @foreach ($booking->bookingItems as $item)
                        <tr>
                            <td>{{ $itemNumber++ }}</td>
                            <td>
                                {{ $item->package->package_name }}
                                @if($item->for_whom_name)
                                    <br><small class="text-muted">For: {{ $item->for_whom_name }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->item_pax }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->item_start_time)->format('h:i A') }}</td>
                            <td class="text-center">{{ $item->item_duration_minutes }} Mins</td>
                            <td class="text-end">RM{{ number_format($item->item_price, 2) }}</td>
                            <td class="text-end">RM{{ number_format($item->item_price * $item->item_pax, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Grand Total:</th>
                        <th class="text-end">RM{{ number_format($invoice->total_price, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($booking->notes)
            <div class="mb-4">
                <strong>Notes:</strong><br>
                <p class="text-muted">{{ $booking->notes }}</p>
            </div>
        @endif

        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted small">Thank you for your business!</p>
                <p class="text-muted small">This is a system-generated invoice and may not require a signature.</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('bookings.show', $booking->booking_id) }}" class="btn btn-secondary me-2">Back to Booking Details</a>
            <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
            {{-- <a href="{{ route('invoices.downloadPdf', $invoice->invoice_id) }}" class="btn btn-success ms-2">Download PDF</a> --}}
        </div>
    </div>
</div>
@endsection
