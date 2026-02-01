<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $data = [
            'totalMembers' => Member::where('is_active', true)->count(),
            'totalProducts' => Product::where('is_active', true)->count(),
            'lowStockProducts' => Product::whereColumn('stock_quantity', '<=', 'min_stock')->count(),
            'todaySales' => Sale::whereDate('created_at', $today)->sum('total'),
            'todaySalesCount' => Sale::whereDate('created_at', $today)->count(),
            'monthSales' => Sale::where('created_at', '>=', $thisMonth)->sum('total'),
            'monthSalesCount' => Sale::where('created_at', '>=', $thisMonth)->count(),
            'recentSales' => Sale::with('member', 'user')->latest()->limit(10)->get(),
            'lowStockItems' => Product::with('category')
                ->whereColumn('stock_quantity', '<=', 'min_stock')
                ->limit(10)->get(),
        ];

        return view('dashboard', $data);
    }

    public function salesChart(Request $request)
    {
        $days = $request->get('days', 7);
        $sales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($sales);
    }
}
