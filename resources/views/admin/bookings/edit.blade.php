@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4">Edit Booking (ID: {{ $booking->booking_id }})</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.bookings.update', $booking->booking_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="user_info" class="form-label">Customer Info</label>
                <input type="text" class="form-control" id="user_info" value="{{ $booking->user->name }} ({{ $booking->user->email }})" disabled>
                <small class="form-text text-muted">Customer cannot be changed from this form. If needed, you might need to create a new booking.</small>
            </div>

            <div class="mb-3">
                <label for="booking_date" class="form-label">Booking Date</label>
                <input type="date" class="form-control" id="booking_date" name="booking_date" value="{{ old('booking_date', \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount (RM)</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}" step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ old('payment_method', $booking->payment_method) }}" required>
            </div>

            <div class="mb-3">
                <label for="booking_status" class="form-label">Booking Status</label>
                <select class="form-select" id="booking_status" name="booking_status" required>
                    <option value="Pending" {{ old('booking_status', $booking->booking_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Confirmed" {{ old('booking_status', $booking->booking_status) == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Cancelled" {{ old('booking_status', $booking->booking_status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="Completed" {{ old('booking_status', $booking->booking_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $booking->notes) }}</textarea>
            </div>
            <h5 class="mt-4">Booked Packages (Read-only for now)</h5>
            @if ($booking->bookingItems->isEmpty())
                <p>No packages in this booking.</p>
            @else
                <ul class="list-group mb-3">
                    @foreach ($booking->bookingItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item->package->package_name }}</strong>
                                ({{ $item->item_pax }} Pax) @ {{ \Carbon\Carbon::parse($item->item_start_time)->format('h:i A') }}
                                @if($item->for_whom_name) - For: {{ $item->for_whom_name }} @endif
                            </div>
                            <span>RM{{ number_format($item->item_price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <small class="form-text text-muted">To change specific packages/times, you may need to cancel and re-book or implement a more complex item-level editing.</small>
            @endif

            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Update Booking</button>
                <a href="{{ route('admin.bookings.show', $booking->booking_id) }}" class="btn btn-secondary">Back to Details</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Back to All Bookings</a>
            </div>
        </form>
    </div>
</div>
@endsection
