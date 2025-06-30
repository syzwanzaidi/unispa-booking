@extends('layout.layout')

@section('content')
<div class="container mt-5 text-center">
    <h3>📧 Please verify your email address</h3>
    <p>We’ve sent a link to your email. Click it to continue.</p>
    @if (session('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary mt-3">Resend Verification Email</button>
    </form>
</div>
@endsection
