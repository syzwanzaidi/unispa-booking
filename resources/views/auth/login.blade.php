@extends('layout.layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - var(--header-height) - var(--footer-height));">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h2 class="card-title text-center mb-4">Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">Don't have an account? Register here.</a>
            </div>
        </form>
    </div>
</div>
@endsection
