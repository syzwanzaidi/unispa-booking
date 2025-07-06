@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Our Packages</h1>

    @if ($categorizedPackages->isEmpty())
        <div class="alert alert-info">
            No packages available at the moment.
        </div>
    @else
        @foreach ($categorizedPackages as $category => $packageGroups)
            <h2 class="mt-4 mb-3">{{ $category }}</h2>
            <div class="row">
                @foreach ($packageGroups as $packageGroup)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ $packageGroup->package_name }}</h5>
                                @if ($packageGroup->package_desc)
                                    <p class="card-text">{{ $packageGroup->package_desc }}</p>
                                @endif
                                <p class="card-text mt-3"><strong>Available Options:</strong></p>
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach ($packageGroup->options as $option)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($option->duration && $option->duration != 'N/A')
                                                    {{ $option->duration }} -
                                                @endif
                                                RM {{ number_format($option->package_price, 2) }}
                                                <br><small class="text-muted">Capacity: {{ $option->capacity }}</small>
                                            </div>
                                            @if (Auth::guard('web')->check())
                                                <a href="{{ route('bookings.create', ['package_id' => $option->package_id]) }}" class="btn btn-sm btn-primary">Book Now</a>
                                            @elseif (Auth::guard('admin')->check())
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to Book</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

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
