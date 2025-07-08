@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Change My Password</h1>

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
            Update Password
        </div>
        <div class="card-body text-black">
            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-warning text-black">Update Password</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary text-black">Back to Profile Settings</a>
    </div>
</div>
@endsection
