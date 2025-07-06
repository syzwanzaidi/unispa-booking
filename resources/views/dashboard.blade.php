@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4 text-primary text-center">User Dashboard</h1>

        @auth('web')
            <div class="card p-4">
                <h1 class="mb-3 text-center text-black">Welcome, {{ $user->name }}!</h1>
                <p style="color: black; font-weight: bold;">Your Email: {{ $user->email }}</p>
                <p style="color: black; font-weight: bold;">Gender: {{ $user->gender ?? 'N/A' }}</p>
                <p style="color: black; font-weight: bold;">Phone Number: {{ $user->phone_no ?? 'N/A' }}</p>
                <p style="color: black; font-weight: bold;">Member Status: {{ $user->is_member ? 'Yes' : 'No' }}</p>

                <p class="mt-4 text-center" style="color: black; font-weight: bold;">This is your personalized dashboard. You are logged in.</p>
                <p class="text-center" style="color: black; font-weight: bold;">You can now access protected content and features.</p>
            </div>

            <hr class="my-4">

            <h2 class="mb-3 text-center">Quick Actions</h2>
            <div class="row">
                {{-- My Bookings --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">My Bookings</h5>
                            <p class="card-text">View and manage your upcoming and past bookings.</p>
                            <a href="{{ route('bookings.index') }}" class="btn btn-primary" style="color: black;">View My Bookings </a>
                        </div>
                    </div>
                </div>

                {{-- My Invoices --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">My Invoices</h5>
                            <p class="card-text">Access your invoice history for all your services.</p>
                            <a href="{{ route('invoices.index') }}" class="btn btn-primary" style="color: black;">View My Invoices</a>
                        </div>
                    </div>
                </div>

                {{-- My Payments --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">My Payments</h5>
                            <p class="card-text">Review your payment transaction records.</p>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary" style="color: black;">View My Payments</a>
                        </div>
                    </div>
                </div>

                {{-- NEW: Manage Account Button --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center">Manage My Account</h5>
                            <p class="card-text">Update your personal information and password.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary" style="color: black;">Manage Account</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg">Logout</button>
                </form>
            </div>
        @else
            <p class="alert alert-warning text-center">You are not logged in as a user.</p>
            <a href="{{ route('login') }}" class="btn btn-primary d-block mx-auto" style="max-width: 200px;">Go to Login</a>
        @endauth
    </div>
</div>
@endsection
