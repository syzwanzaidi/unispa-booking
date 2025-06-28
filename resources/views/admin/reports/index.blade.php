@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h1 class="mb-4 text-primary">Reports Dashboard</h1>

        <p class="lead">Here you can generate various reports to monitor UniSPA's performance.</p>

        <hr class="my-4">

        <h2 class="mb-3">Sales Overview</h2>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Today's Sales</div>
                    <div class="card-body">
                        <h4 class="card-title">RM{{ number_format($totalSalesToday, 2) }}</h4>
                        <p class="card-text">Total sales generated today.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">This Month's Sales</div>
                    <div class="card-body">
                        <h4 class="card-title">RM{{ number_format($totalSalesThisMonth, 2) }}</h4>
                        <p class="card-text">Total sales generated in the current month.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">This Year's Sales</div>
                    <div class="card-body">
                        <h4 class="card-title">RM{{ number_format($totalSalesThisYear, 2) }}</h4>
                        <p class="card-text">Total sales generated in the current year.</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mb-3">Detailed Sales Reports</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">Daily Sales Report</h5>
                        <p class="card-text">View sales data for a specific day.</p>
                        <a href="{{ route('admin.reports.sales.daily') }}" class="btn btn-primary">View Daily Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Sales Report</h5>
                        <p class="card-text">Summarized sales data broken down by day for a month.</p>
                        <a href="{{ route('admin.reports.sales.monthly') }}" class="btn btn-primary">View Monthly Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">Yearly Sales Report</h5>
                        <p class="card-text">Summarized sales data broken down by month for a year.</p>
                        <a href="{{ route('admin.reports.sales.yearly') }}" class="btn btn-primary">View Yearly Report</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Admin Dashboard</a>
        </div>
    </div>
</div>
@endsection
