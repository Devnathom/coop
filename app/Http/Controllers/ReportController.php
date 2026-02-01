<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Member;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $sales = Sale::with(['member', 'user'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->get();

        $summary = [
            'total_sales' => $sales->sum('total'),
            'total_transactions' => $sales->count(),
            'total_discount' => $sales->sum('discount'),
            'average_sale' => $sales->count() > 0 ? $sales->sum('total') / $sales->count() : 0,
        ];

        $dailySales = Sale::selectRaw('DATE(created_at) as date, SUM(total) as total, COUNT(*) as count')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.sales', compact('sales', 'summary', 'dailySales', 'dateFrom', 'dateTo'));
    }

    public function products(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $topProducts = SaleItem::select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_sales')
            ->selectRaw('SUM((unit_price - cost_price) * quantity) as total_profit')
            ->whereHas('sale', function($q) use ($dateFrom, $dateTo) {
                $q->whereDate('created_at', '>=', $dateFrom)
                  ->whereDate('created_at', '<=', $dateTo);
            })
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();

        $categoryStats = SaleItem::select('products.category_id')
            ->selectRaw('SUM(sale_items.quantity) as total_quantity')
            ->selectRaw('SUM(sale_items.subtotal) as total_sales')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereHas('sale', function($q) use ($dateFrom, $dateTo) {
                $q->whereDate('created_at', '>=', $dateFrom)
                  ->whereDate('created_at', '<=', $dateTo);
            })
            ->groupBy('products.category_id')
            ->with('product.category')
            ->get();

        return view('reports.products', compact('topProducts', 'categoryStats', 'dateFrom', 'dateTo'));
    }

    public function members(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $topMembers = Sale::select('member_id')
            ->selectRaw('SUM(total) as total_purchase')
            ->selectRaw('COUNT(*) as transaction_count')
            ->whereNotNull('member_id')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('member_id')
            ->with('member')
            ->orderByDesc('total_purchase')
            ->limit(20)
            ->get();

        $memberStats = [
            'total_members' => Member::where('is_active', true)->count(),
            'new_members' => Member::whereDate('join_date', '>=', $dateFrom)
                ->whereDate('join_date', '<=', $dateTo)->count(),
            'total_shares' => Member::where('is_active', true)->sum('share_amount'),
        ];

        return view('reports.members', compact('topMembers', 'memberStats', 'dateFrom', 'dateTo'));
    }

    public function profit(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $profitData = SaleItem::selectRaw('DATE(sales.created_at) as date')
            ->selectRaw('SUM(sale_items.subtotal) as revenue')
            ->selectRaw('SUM(sale_items.cost_price * sale_items.quantity) as cost')
            ->selectRaw('SUM(sale_items.subtotal - (sale_items.cost_price * sale_items.quantity)) as profit')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereDate('sales.created_at', '>=', $dateFrom)
            ->whereDate('sales.created_at', '<=', $dateTo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = [
            'total_revenue' => $profitData->sum('revenue'),
            'total_cost' => $profitData->sum('cost'),
            'total_profit' => $profitData->sum('profit'),
            'profit_margin' => $profitData->sum('revenue') > 0 
                ? ($profitData->sum('profit') / $profitData->sum('revenue')) * 100 
                : 0,
        ];

        return view('reports.profit', compact('profitData', 'summary', 'dateFrom', 'dateTo'));
    }

    public function stock()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->get();

        $summary = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->stock_quantity * $p->cost_price),
            'low_stock_count' => $products->filter(fn($p) => $p->stock_quantity <= $p->min_stock)->count(),
            'out_of_stock' => $products->filter(fn($p) => $p->stock_quantity == 0)->count(),
        ];

        return view('reports.stock', compact('products', 'summary'));
    }
}
