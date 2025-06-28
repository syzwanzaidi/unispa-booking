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
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-primary mb-2">Add/View Packages</a>
                        </div>
                    </div>
                </div>

                {{-- Manage Customer Information --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">Manage Customer Information</h5>
                            <p class="card-text">View, add, update, and delete customer accounts.</p>
                            <a href="#" class="btn btn-primary disabled mb-2" title="Implement link to customer list/creation">Add/View Customers</a>
                        </div>
                    </div>
                </div>

                {{-- Generate Reports --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">Generate Reports</h5>
                            <p class="card-text">Generate daily, monthly, and yearly operational reports.</p>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-info">Go to Reports Dashboard</a> {{-- UPDATED LINK --}}
                        </div>
                    </div>
                </div>

                {{-- View All Bookings --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">View All Bookings</h5>
                            <p class="card-text">Oversee all customer bookings in the system and search.</p>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">View Bookings</a>
                        </div>
                    </div>
                </div>

                {{-- View All Invoices --}}
                <div class="col-md-4 mb-3">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h5 class="card-title">View All Invoices</h5>
                            <p class="card-text">Access and view all generated invoices.</p>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary">View Invoices</a>
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
