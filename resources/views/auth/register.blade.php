@extends('layout.layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - var(--header-height) - var(--footer-height));">
    <div class="card p-4" style="width: 100%; max-width: 500px;">
        <h2 class="card-title text-center mb-4">Register</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            {{-- NEW: Gender --}}
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- NEW: Phone Number --}}
            <div class="mb-3">
                <label for="phone_no" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no') }}">
            </div>

            {{-- NEW: Is Member --}}
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_member" name="is_member" value="1" {{ old('is_member') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_member">Are you a member?</label>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">Already registered? Login here.</a>
            </div>
        </form>
    </div>
</div>
@endsection
