<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $totalSalesToday = Invoice::whereDate('generated_at', Carbon::today())
                                ->sum('total_price');
        $totalSalesThisMonth = Invoice::whereMonth('generated_at', Carbon::now()->month)
                                    ->whereYear('generated_at', Carbon::now()->year)
                                    ->sum('total_price');
        $totalSalesThisYear = Invoice::whereYear('generated_at', Carbon::now()->year)
                                   ->sum('total_price');

        return view('admin.reports.index', compact(
            'totalSalesToday', 'totalSalesThisMonth', 'totalSalesThisYear'
        ));
    }
    public function dailySales(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $invoices = Invoice::with('booking.user')
                           ->whereDate('generated_at', $date)
                           ->orderBy('generated_at', 'asc')
                           ->get();

        $totalSales = $invoices->sum('total_price');

        return view('admin.reports.sales.daily', compact('invoices', 'totalSales', 'date'));
    }
    public function monthlySales(Request $request)
    {
        $month = $request->input('month') ? Carbon::parse($request->input('month') . '-01') : Carbon::now();
        $dailySales = Invoice::select(
                                DB::raw('DATE(generated_at) as date'),
                                DB::raw('SUM(total_price) as total_sales')
                            )
                            ->whereYear('generated_at', $month->year)
                            ->whereMonth('generated_at', $month->month)
                            ->groupBy(DB::raw('DATE(generated_at)'))
                            ->orderBy('date', 'asc')
                            ->get();

        $totalSales = $dailySales->sum('total_sales');

        return view('admin.reports.sales.monthly', compact('dailySales', 'totalSales', 'month'));
    }
    public function yearlySales(Request $request)
    {
        $year = $request->input('year') ? (int)$request->input('year') : Carbon::now()->year;
        $monthlySales = Invoice::select(
                                DB::raw('MONTH(generated_at) as month_number'),
                                DB::raw('SUM(total_price) as total_sales')
                            )
                            ->whereYear('generated_at', $year)
                            ->groupBy(DB::raw('MONTH(generated_at)'))
                            ->orderBy('month_number', 'asc')
                            ->get()
                            ->map(function ($item) {
                                // Convert month number to month name for display
                                $item->month_name = Carbon::create()->month($item->month_number)->format('F');
                                return $item;
                            });

        $totalSales = $monthlySales->sum('total_sales');

        return view('admin.reports.sales.yearly', compact('monthlySales', 'totalSales', 'year'));
    }
}
