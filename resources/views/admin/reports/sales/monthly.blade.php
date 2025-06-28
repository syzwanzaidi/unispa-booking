@extends('layout.layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Monthly Sales Report - {{ $month->format('F Y') }}</h1>

    {{-- Month Picker for Monthly Report --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">Select Month</div>
        <div class="card-body">
            <form action="{{ route('admin.reports.sales.monthly') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">Month:</label>
                    <input type="month" class="form-control" id="month" name="month" value="{{ $month->format('Y-m') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">View Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info text-center lead">
        Total Sales for {{ $month->format('F Y') }}: <strong>RM{{ number_format($totalSales, 2) }}</strong>
    </div>

    @if ($dailySales->isEmpty())
        <div class="alert alert-warning">
            No sales recorded for this month.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Daily Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dailySales as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                            <td>RM{{ number_format($day->total_sales, 2) }}</td>
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
