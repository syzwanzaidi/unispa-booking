@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Payment Details #{{ $payment->payment_id }}</h1>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Payment Information
        </div>
        <div class="card-body">
            <p><strong>Payment ID:</strong> {{ $payment->payment_id }}</p>
            <p><strong>Invoice Number:</strong> {{ $payment->invoice->invoice_number ?? 'N/A' }}</p>
            <p><strong>Amount Paid:</strong> RM {{ number_format($payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s') : 'N/A' }}</p>
            <p><strong>Status:</strong>
                @if ($payment->payment_status == 'Completed')
                    <span class="badge bg-success">{{ $payment->payment_status }}</span>
                @elseif ($payment->payment_status == 'Pending')
                    <span class="badge bg-warning">{{ $payment->payment_status }}</span>
                @else
                    <span class="badge bg-secondary">{{ $payment->payment_status }}</span>
                @endif
            </p>
        </div>
    </div>

    @if ($payment->invoice)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                Associated Invoice Details (Invoice ID: {{ $payment->invoice->invoice_id }})
            </div>
            <div class="card-body">
                <p><strong>Invoice Total:</strong> RM {{ number_format($payment->invoice->total_price, 2) }}</p>
                <p><strong>Invoice Status:</strong> {{ $payment->invoice->payment_status }}</p>
                <p><strong>Booking ID:</strong> {{ $payment->invoice->booking->booking_id ?? 'N/A' }}</p>
                <p><strong>Customer:</strong> {{ $payment->invoice->booking->user->name ?? 'N/A' }}</p>
                <a href="{{ route('invoices.show', $payment->invoice->invoice_id) }}" class="btn btn-sm btn-outline-info">View Invoice</a>
            </div>
        </div>
    @endif

    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back to All Payments</a>
</div>
@endsection
