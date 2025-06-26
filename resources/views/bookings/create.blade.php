@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Create New Booking</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
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

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf

        <div class="mb-3">
            <label for="package_id" class="form-label">Select Package</label>
            <select class="form-select" id="package_id" name="package_id" required>
                <option value="">-- Choose a Package --</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->package_id }}"
                        {{ old('package_id', $selectedPackageId) == $package->package_id ? 'selected' : '' }}
                        data-capacity="{{ $package->capacity }}">
                        {{ $package->package_name }} - {{ $package->package_desc }} (RM {{ number_format($package->package_price, 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="booking_pax" class="form-label">Number of Pax</label>
            <input type="number" class="form-control" id="booking_pax" name="booking_pax" value="{{ old('booking_pax') }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="booking_date" class="form-label">Booking Date</label>
            <input type="date" class="form-control" id="booking_date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="booking_time" class="form-label">Booking Time</label>
            <input type="time" class="form-control" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="Online Banking" {{ old('payment_method') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash (at Spa)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Booking</button>
    </form>
</div>
@endsection
