@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Bookings</h1>

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

    @if ($bookings->isEmpty())
        <div class="alert alert-info">
            You have no bookings yet.
            <a href="{{ route('bookings.create') }}" class="alert-link">Click here to make one!</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Packages Booked</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                            <td>RM{{ number_format($booking->total_amount, 2) }}</td>
                            <td>{{ $booking->booking_status }}</td>
                            <td>{{ $booking->payment_method }}</td>
                            <td>
                                @foreach ($booking->bookingItems as $item)
                                    - {{ $item->package->package_name }} ({{ $item->item_pax }} Pax @ {{ \Carbon\Carbon::parse($item->item_start_time)->format('h:i A') }})
                                    @if($item->for_whom_name) for {{ $item->for_whom_name }} @endif <br>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
