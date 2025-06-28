@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Booking Details (ID: {{ $booking->booking_id }})</h1>

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

    <div class="card mb-4">
        <div class="card-header">Overall Booking Information</div>
        <div class="card-body">
            <p><strong>Booking ID:</strong> {{ $booking->booking_id }}</p>
            <p><strong>Customer:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
            <p><strong>Total Amount:</strong> RM{{ number_format($booking->total_amount, 2) }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{
                    $booking->booking_status == 'Confirmed' ? 'success' :
                    ($booking->booking_status == 'Pending' ? 'warning text-dark' :
                    ($booking->booking_status == 'Cancelled' ? 'danger' : 'secondary'))
                }}">{{ $booking->booking_status }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ $booking->payment_method }}</p>
            @if ($booking->notes)
                <p><strong>Notes:</strong> {{ $booking->notes }}</p>
            @endif
            <p><strong>Created At:</strong> {{ $booking->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>Last Updated:</strong> {{ $booking->updated_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Booked Packages</div>
        <div class="card-body">
            @if ($booking->bookingItems->isEmpty())
                <p>No packages found for this booking.</p>
            @else
                <ul class="list-group">
                    @foreach ($booking->bookingItems as $item)
                        <li class="list-group-item">
                            <h5>{{ $item->package->package_name }}</h5>
                            <p>
                                <strong>Description:</strong> {{ $item->package->package_desc }}<br>
                                <strong>Pax:</strong> {{ $item->item_pax }}<br>
                                <strong>Time:</strong> {{ \Carbon\Carbon::parse($item->item_start_time)->format('h:i A') }}<br>
                                <strong>Duration:</strong> {{ $item->item_duration_minutes }} Minutes<br>
                                <strong>Price:</strong> RM{{ number_format($item->item_price, 2) }}
                                @if($item->for_whom_name)
                                    <br><strong>For:</strong> {{ $item->for_whom_name }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Back to All Bookings</a>
        <a href="{{ route('admin.bookings.edit', $booking->booking_id) }}" class="btn btn-warning">Edit Booking</a>

        @if ($booking->booking_status !== 'Cancelled' && $booking->booking_status !== 'Completed')
            <form action="{{ route('admin.bookings.cancel', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone and may require manual refund processing.');">
                @csrf
                <button type="submit" class="btn btn-danger">Cancel Booking</button>
            </form>
        @endif

        @if($booking->invoice)
            <a href="{{ route('admin.invoices.show', $booking->invoice->invoice_id) }}" class="btn btn-info btn-sm">View Invoice</a>
        @else
            <span class="text-muted">No Invoice Yet</span>
        @endif
    </div>
</div>
@endsection
