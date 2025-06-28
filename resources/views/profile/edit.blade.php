@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Manage My Account</h1>

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

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Update Profile Information
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" @if(old('gender', $user->gender) == 'male') selected @endif>Male</option>
                        <option value="female" @if(old('gender', $user->gender) == 'female') selected @endif>Female</option>
                        <option value="other" @if(old('gender', $user->gender) == 'other') selected @endif>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="phone_no" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            Change Password
        </div>
        <div class="card-body">
            <p>For security, please use a strong, unique password.</p>
            <a href="{{ route('profile.change-password') }}" class="btn btn-warning">Change Password</a>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
@endsection
