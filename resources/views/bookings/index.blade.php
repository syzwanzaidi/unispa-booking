@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">My Bookings</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($bookings->isEmpty())
        <div class="alert alert-info">
            You have no bookings yet.
            <a href="{{ route('bookings.create') }}" class="alert-link">Make a booking now!</a>
        </div>
    @else
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Package Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Pax</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td>{{ $booking->package->package_name }} - {{ $booking->package->package_desc }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</td>
                    <td>{{ $booking->booking_pax }}</td>
                    <td>{{ $booking->booking_status }}</td>
                    <td>{{ $booking->payment_method }}</td>
                    <td>
                        <a href="{{ route('bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm">View</a>
                        {{-- @if ($booking->booking_status == 'Pending')
                            <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('bookings.destroy', $booking->booking_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                            </form>
                        @endif --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
