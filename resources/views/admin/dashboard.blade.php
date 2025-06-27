@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4 text-primary">Admin Dashboard</h1>
        @auth('admin')
            <p class="lead">Welcome back, <strong>{{ Auth::guard('admin')->user()->admin_username }}</strong>!</p>
            <p>You have successfully logged in as an administrator. From here, you can manage the UniSPA system.</p>

            <hr class="my-4">

            <h2 class="mb-3">System Management & Reporting</h2>
            <div class="row">

                {{-- Manage Spa Packages --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">Manage Spa Packages</h5>
                            <p class="card-text">Add, update, and delete spa packages offered.</p>
                            <a href="#" class="btn btn-primary disabled mb-2" title="Implement link to package list/creation">Add/View Packages</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">Manage Customer Information</h5>
                            <p class="card-text">View, add, update, and delete customer accounts.</p>
                            <a href="#" class="btn btn-primary disabled mb-2" title="Implement link to customer list/creation">Add/View Customers</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">Generate Reports</h5>
                            <p class="card-text">Generate daily, monthly, and yearly operational reports.</p>
                            <div class="d-flex flex-column">
                                <a href="#" class="btn btn-info disabled mb-2" title="Implement link to daily report">Daily Report</a>
                                <a href="#" class="btn btn-info disabled mb-2" title="Implement link to monthly report">Monthly Report</a>
                                <a href="#" class="btn btn-info disabled" title="Implement link to yearly report">Yearly Report</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">View All Bookings</h5>
                            <p class="card-text">Oversee all customer bookings in the system.</p>
                            <a href="#" class="btn btn-primary disabled" title="Implement link to all bookings list">View Bookings</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4 text-center">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg">Logout Admin</button>
                </form>
            </div>
        @else
            <p class="alert alert-warning text-center">You are not authorized to view this page. Please log in as an admin.</p>
            <a href="{{ route('login') }}" class="btn btn-primary d-block mx-auto" style="max-width: 200px;">Go to Login</a>
        @endauth
    </div>
</div>
@endsection
