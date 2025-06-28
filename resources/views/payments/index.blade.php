@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Payments</h1>

    @if ($payments->isEmpty())
        <div class="alert alert-info">
            You currently have no payment records.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Invoice Number</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_id }}</td>
                            <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                            <td>RM {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                @if ($payment->payment_status == 'Completed')
                                    <span class="badge bg-success">{{ $payment->payment_status }}</span>
                                @elseif ($payment->payment_status == 'Pending')
                                    <span class="badge bg-warning">{{ $payment->payment_status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $payment->payment_status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment->payment_id) }}" class="btn btn-sm btn-info">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
