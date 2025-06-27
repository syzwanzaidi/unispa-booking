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
            <p><strong>User:</strong> {{ $booking->user->name }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>
            <p><strong>Total Amount:</strong> RM{{ number_format($booking->total_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ $booking->booking_status }}</p>
            <p><strong>Payment Method:</strong> {{ $booking->payment_method }}</p>
            @if ($booking->notes)
                <p><strong>Notes:</strong> {{ $booking->notes }}</p>
            @endif
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

    <div class="mt-4">
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to My Bookings</a>
        @if ($booking->booking_status !== 'Cancelled' && $booking->booking_status !== 'Completed' && \Carbon\Carbon::parse($booking->booking_date)->isFuture() || \Carbon\Carbon::parse($booking->booking_date)->isToday())
            <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                @csrf
                <button type="submit" class="btn btn-danger">Cancel Booking</button>
            </form>
        @else
            <button type="button" class="btn btn-warning" disabled>Cannot Cancel ({{ $booking->booking_status }})</button>
        @endif
    </div>
</div>
@endsection
