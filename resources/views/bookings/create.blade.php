@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Create New Booking</h1>

    {{-- Error and Success Messages --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
        @csrf

        {{-- Overall Booking Details --}}
        <div class="card mb-4">
            <div class="card-header">Overall Booking Information</div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="booking_date">Booking Date</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date"
                           value="{{ old('booking_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                           min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                    @error('booking_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="">Select payment method</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Online Banking" {{ old('payment_method') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                    </select>
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Notes (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        {{-- MASTER TEMPLATE FOR CLONING (MOVED AND HIDDEN) --}}
        <div id="packageItemMasterTemplate" style="display:none;">
            <div class="card mb-3 package-item-template">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Package Item <span class="item-number">1</span>
                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>Select Package</label>
                        <select class="form-control package-select" name="items[0][package_id]" required disabled>
                            <option value="">Choose a package</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->package_id }}"
                                        data-duration="{{ $package->duration }}"
                                        data-price="{{ $package->package_price }}">
                                    {{ $package->package_name }} ({{ $package->package_desc }}) - RM{{ number_format($package->package_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Number of People (Pax)</label>
                        <input type="number" class="form-control pax-input" name="items[0][item_pax]" value="1" min="1" required disabled>
                    </div>

                    <div class="form-group mb-3">
                        <label>Time Slot</label>
                        <select class="form-control time-slot-select" name="items[0][item_start_time]" required disabled>
                            <option value="">Select a time slot</option>
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot }}">
                                    {{ \Carbon\Carbon::parse($slot)->format('h:i A') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>For Whom (Name, optional)</label>
                        <input type="text" class="form-control for-whom-input" name="items[0][for_whom_name]" placeholder="e.g., Ali Samad (leave empty for self)" disabled>
                    </div>
                </div>
            </div>
        </div>
        {{-- END MASTER TEMPLATE --}}
        <div id="packageItemsContainer">
        </div>

        <button type="button" class="btn btn-secondary mb-4" id="addPackageItem">Add Another Package</button>

        <button type="submit" class="btn btn-primary">Submit Booking</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

{{-- Pass necessary data to JavaScript using global variables --}}
<script>
    window.oldBookingItems = @json(old('items') ?? []);
    window.allPackages = @json($packages);
    window.allTimeSlots = @json($timeSlots);
    window.selectedInitialPackageId = @json($selectedPackageId);
</script>

@endsection
