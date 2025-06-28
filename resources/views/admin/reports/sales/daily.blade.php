@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Daily Sales Report - {{ $date->format('M d, Y') }}</h1>

    {{-- Date Picker for Daily Report --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Select Date</div>
        <div class="card-body">
            <form action="{{ route('admin.reports.sales.daily') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $date->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">View Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info text-center lead">
        Total Sales for {{ $date->format('M d, Y') }}: <strong>RM{{ number_format($totalSales, 2) }}</strong>
    </div>

    @if ($invoices->isEmpty())
        <div class="alert alert-warning">
            No sales recorded for this date.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice ID</th>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Booking ID</th>
                        <th>Total Price</th>
                        <th>Payment Status</th>
                        <th>Generated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_id }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $invoice->booking_id }}</td>
                            <td>RM{{ number_format($invoice->total_price, 2) }}</td>
                            <td><span class="badge bg-{{
                                    $invoice->payment_status === 'Paid' ? 'success' : 'bg-warning text-dark'
                                }}">{{ $invoice->payment_status }}</span>
                            </td>
                            <td>{{ $invoice->generated_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.invoices.show', $invoice->invoice_id) }}" class="btn btn-info btn-sm">View Invoice</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back to Reports Dashboard</a>
    </div>
</div>
@endsection
