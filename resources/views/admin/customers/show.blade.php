@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Customer Details: {{ $customer->name }}</h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Customer Information
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $customer->id }}</p>
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone Number:</strong> {{ $customer->phone_no }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($customer->gender) }}</p>
            <p><strong>Registered At:</strong> {{ $customer->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Last Updated At:</strong> {{ $customer->updated_at->format('Y-m-d H:i:s') }}</p>
            <hr>
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning me-2">Edit Customer</a>
            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer and all associated data? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Customer</button>
            </form>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
            Customer Bookings
        </div>
        <div class="card-body">
            @if ($customer->bookings->isEmpty())
                <p>This customer has no bookings.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Invoice Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_id }}</td>
                                    <td>{{ $booking->booking_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $booking->booking_status }}</td>
                                    <td>RM {{ number_format($booking->total_amount, 2) }}</td>
                                    <td>{{ $booking->payment_method }}</td>
                                    <td>{{ $booking->invoice->payment_status ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking->booking_id) }}" class="btn btn-sm btn-info">View Booking</a>
                                        @if($booking->invoice)
                                            <a href="{{ route('admin.invoices.show', $booking->invoice->invoice_id) }}" class="btn btn-sm btn-primary ms-1">View Invoice</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back to Customers List</a>
</div>
@endsection
