@extends('layout.layout')

@section('content')
<div class="container mt-4">
    <div class="card p-4">
        <h1 class="mb-3">Welcome, {{ $user->name }}!</h1>
        <p>Your Email: {{ $user->email }}</p>
        <p>Gender: {{ $user->gender ?? 'N/A' }}</p>
        <p>Phone Number: {{ $user->phone_no ?? 'N/A' }}</p>
        <p>Member Status: {{ $user->is_member ? 'Yes' : 'No' }}</p>

        <p class="mt-4">This is your personalized dashboard. You are logged in.</p>
        <p>You can now access protected content and features.</p>
    </div>
    <div class="mt-4">
        <h2>Quick Actions</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">My Bookings</h5>
                        <p class="card-text">View your past and upcoming booking history, or cancel a booking.</p>
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary">View Bookings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Book a New Session</h5>
                        <p class="card-text">Ready to enjoy another session? Create a new booking now!</p>
                        <a href="{{ route('bookings.create') }}" class="btn btn-success">Make a Booking</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
