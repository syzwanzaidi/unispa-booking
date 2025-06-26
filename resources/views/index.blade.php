@extends('layout.layout')

@section('content')

<div class="container text-center py-5">
    <h1>Welcome to UniSPA!</h1>
    <p class="lead">Your sanctuary for relaxation and rejuvenation.</p>

    <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg mt-4">
        View Our Services & Book Now
    </a>
</div>

@endsection
