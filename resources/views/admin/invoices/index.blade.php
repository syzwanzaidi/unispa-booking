@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">All Invoices</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($invoices->isEmpty())
        <div class="alert alert-info">
            No invoices found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_id }}</td>
                            <td>{{ $invoice->booking_id }}</td>
                            <td>{{ $invoice->booking->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</td>
                            <td>RM{{ number_format($invoice->total_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $invoice->payment_status === 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $invoice->payment_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.invoices.show', $invoice->invoice_id) }}" class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
