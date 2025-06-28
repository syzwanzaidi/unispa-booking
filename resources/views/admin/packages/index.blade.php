@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Manage Spa Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-success">Add New Package</a>
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

    @if ($packages->isEmpty())
        <div class="alert alert-info">
            No packages found. Click "Add New Package" to get started.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td>{{ $package->package_id }}</td>
                            <td>{{ $package->package_name }}</td>
                            <td>{{ $package->package_desc ?? 'N/A' }}</td>
                            <td>RM{{ number_format($package->package_price, 2) }}</td>
                            <td>{{ $package->duration ?? 'N/A' }}</td>
                            <td>{{ $package->capacity }}</td>
                            <td>
                                <a href="{{ route('admin.packages.edit', $package->package_id) }}" class="btn btn-info btn-sm me-2">Edit</a>
                                <form action="{{ route('admin.packages.destroy', $package->package_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
