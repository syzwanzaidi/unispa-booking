@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Customer: {{ $customer->name }}</h1>

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

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            Update Customer Details
        </div>
        <div class="card-body">
            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" @if(old('gender', $customer->gender) == 'male') selected @endif>Male</option>
                        <option value="female" @if(old('gender', $customer->gender) == 'female') selected @endif>Female</option>
                        <option value="other" @if(old('gender', $customer->gender) == 'other') selected @endif>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="phone_no" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no', $customer->phone_no) }}" required>
                </div>

                <hr>
                <h5 class="mt-4">Change Password (Optional)</h5>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-warning">Update Customer</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Back to Customers List</a>
    </div>
</div>
@endsection
