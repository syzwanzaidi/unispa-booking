@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Our Packages</h1>

    @if ($categorizedPackages->isEmpty())
        <div class="alert alert-info">
            No packages available at the moment.
        </div>
    @else
        @foreach ($categorizedPackages as $category => $packages)
            <h2 class="mt-4 mb-3">{{ $category }}</h2>
            <div class="row">
                @foreach ($packages as $package)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $package->package_name }}</h5>
                                <p class="card-text">{{ $package->package_desc }}</p>
                                <p class="card-text"><strong>Price:</strong> RM {{ number_format($package->package_price, 2) }}</p>
                                @if ($package->duration != 'N/A')
                                    <p class="card-text"><strong>Duration:</strong> {{ $package->duration }}</p>
                                @endif
                                <p class="card-text"><small class="text-muted">Max Capacity: {{ $package->capacity }}</small></p>
                                @auth
                                    <a href="{{ route('bookings.create', ['package_id' => $package->package_id]) }}" class="btn btn-sm btn-primary ms-2">Book Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary ms-2">Login to Book</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
        @endforeach
    @endif
</div>
@endsection
