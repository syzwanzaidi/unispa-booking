@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4">Edit Spa Package: {{ $package->package_name }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.packages.update', $package->package_id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Use PUT method for update requests --}}

            <div class="mb-3">
                <label for="package_name" class="form-label">Package Name</label>
                <input type="text" class="form-control" id="package_name" name="package_name" value="{{ old('package_name', $package->package_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="package_desc" class="form-label">Description</label>
                <textarea class="form-control" id="package_desc" name="package_desc" rows="3">{{ old('package_desc', $package->package_desc) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="package_price" class="form-label">Price (RM)</label>
                <input type="number" class="form-control" id="package_price" name="package_price" value="{{ old('package_price', $package->package_price) }}" step="0.01" min="0.01" required>
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Duration (e.g., "60 Minutes", "2 Hours")</label>
                <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration', $package->duration) }}">
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity (Number of people per slot)</label>
                <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $package->capacity) }}" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Package</button>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
