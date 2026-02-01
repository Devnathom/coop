<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(20);
        $products = Product::where('is_active', true)->get();

        return view('stocks.index', compact('movements', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('stocks.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $stockBefore = $product->stock_quantity;

        if ($validated['type'] === 'in') {
            $stockAfter = $stockBefore + $validated['quantity'];
        } elseif ($validated['type'] === 'out') {
            if ($stockBefore < $validated['quantity']) {
                return back()->with('error', 'จำนวนสินค้าในคลังไม่เพียงพอ');
            }
            $stockAfter = $stockBefore - $validated['quantity'];
        } else {
            $stockAfter = $validated['quantity'];
            $validated['quantity'] = abs($stockAfter - $stockBefore);
        }

        DB::transaction(function() use ($validated, $product, $stockBefore, $stockAfter) {
            StockMovement::create([
                'product_id' => $validated['product_id'],
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'cost_price' => $validated['cost_price'] ?? $product->cost_price,
                'note' => $validated['note'],
            ]);

            $product->update(['stock_quantity' => $stockAfter]);
        });

        return redirect()->route('stocks.index')
            ->with('success', 'บันทึกการเคลื่อนไหวสต๊อกเรียบร้อยแล้ว');
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->whereColumn('stock_quantity', '<=', 'min_stock')
            ->where('is_active', true)
            ->paginate(20);

        return view('stocks.low', compact('products'));
    }
}
