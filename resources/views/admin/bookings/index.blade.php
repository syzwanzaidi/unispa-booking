@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">All Bookings</h1>

    {{-- Search Form --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Search Bookings</div>
        <div class="card-body">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="booking_id" class="form-label">Booking ID</label>
                    <input type="text" class="form-control" id="booking_id" name="booking_id" value="{{ $oldInput['booking_id'] ?? '' }}" placeholder="e.g., 123">
                </div>
                <div class="col-md-3">
                    <label for="user_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" value="{{ $oldInput['user_name'] ?? '' }}" placeholder="e.g., Ali">
                </div>
                <div class="col-md-3">
                    <label for="booking_date" class="form-label">Booking Date</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" value="{{ $oldInput['booking_date'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="booking_status" class="form-label">Status</label>
                    <select class="form-select" id="booking_status" name="booking_status">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ ($oldInput['booking_status'] ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ ($oldInput['booking_status'] ?? '') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="Cancelled" {{ ($oldInput['booking_status'] ?? '') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Completed" {{ ($oldInput['booking_status'] ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Clear Search</a>
                </div>
            </form>
        </div>
    </div>

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
            No bookings found matching your criteria.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Packages</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_id }}</td>
                            <td>{{ $booking->user->name }} ({{ $booking->user->email }})</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                            <td>RM{{ number_format($booking->total_amount, 2) }}</td>
                            <td><span class="badge bg-{{
                                    $booking->booking_status == 'Confirmed' ? 'success' :
                                    ($booking->booking_status == 'Pending' ? 'warning text-dark' :
                                    ($booking->booking_status == 'Cancelled' ? 'danger' : 'secondary'))
                                }}">{{ $booking->booking_status }}</span>
                            </td>
                            <td>{{ $booking->payment_method }}</td>
                            <td>
                                @foreach ($booking->bookingItems as $item)
                                    - {{ $item->package->package_name }} ({{ $item->item_pax }} Pax @ {{ \Carbon\Carbon::parse($item->item_start_time)->format('h:i A') }})
                                    @if($item->for_whom_name) for {{ $item->for_whom_name }} @endif <br>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('admin.bookings.edit', $booking->booking_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @if ($booking->booking_status !== 'Cancelled' && $booking->booking_status !== 'Completed')
                                        <form action="{{ route('admin.bookings.cancel', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel booking ID {{ $booking->booking_id }}? This cannot be undone.');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Cancel</button>
                                        </form>
                                    @endif
                                    @if($booking->invoice)
                                        <a href="{{ route('admin.invoices.show', $booking->invoice->invoice_id) }}" class="btn btn-info btn-sm">View Invoice</a>
                                    @else
                                        <span class="text-muted">No Invoice Yet</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->appends($oldInput)->links() }}
        </div>
    @endif
</div>
@endsection
