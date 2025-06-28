@php
    use Carbon\Carbon; // <--- ADD THIS LINE
@endphp

@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Yearly Sales Report - {{ $year }}</h1>

    {{-- Year Picker for Yearly Report --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Select Year</div>
        <div class="card-body">
            <form action="{{ route('admin.reports.sales.yearly') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="year" class="form-label">Year:</label>
                    <input type="number" class="form-control" id="year" name="year" value="{{ $year }}" min="2000" max="{{ Carbon::now()->year }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">View Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info text-center lead">
        Total Sales for {{ $year }}: <strong>RM{{ number_format($totalSales, 2) }}</strong>
    </div>

    @if ($monthlySales->isEmpty())
        <div class="alert alert-warning">
            No sales recorded for this year.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Month</th>
                        <th>Monthly Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlySales as $month)
                        <tr>
                            <td>{{ $month->month_name }}</td>
                            <td>RM{{ number_format($month->total_sales, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Back to Reports Dashboard</a>
    </div>
</div>
@endsection
