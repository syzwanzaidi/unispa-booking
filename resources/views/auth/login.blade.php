@extends('layout.layout')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card p-4 shadow-sm login-form-card">
        <h2 class="text-center mb-4">Login</h2>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="identifier" class="form-label">Email / Username</label>
                {{-- CHANGE: Name the input 'identifier' --}}
                <input type="text" class="form-control @error('identifier') is-invalid @enderror" id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus>
                @error('identifier')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

            <div class="mt-3 text-center">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        </form>
    </div>
</div>
@endsection
