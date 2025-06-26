@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">UNI-SPA SERVICES</h1>
    <h2 class="text-center mb-5">Indulge in Relaxation and Rejuvenation</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        @php
            $groupedPackages = $packages->groupBy('package_name');
        @endphp

        @foreach ($groupedPackages as $packageName => $items)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">{{ $packageName }}</h4>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($items as $package)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $package->package_desc }}</strong>
                            {{ $package->duration !== 'N/A' ? ' - ' . $package->duration : '' }}
                        </div>
                        <div>
                            RM {{ number_format($package->package_price, 2) }}
                            @auth
                                <a href="{{ route('bookings.create', ['package_id' => $package->package_id]) }}" class="btn btn-sm btn-primary ms-2">Book Now</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary ms-2">Login to Book</a>
                            @endauth
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-5">
        <p class="fs-4">10% Discount for UITM Members</p>
    </div>

</div>
@endsection
