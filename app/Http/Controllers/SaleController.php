<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Member;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['member', 'user']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,credit',
            'note' => 'nullable|string|max:500',
        ]);

        $subtotal = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            if ($product->stock_quantity < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "สินค้า {$product->name} มีไม่เพียงพอ (คงเหลือ {$product->stock_quantity})"
                ], 400);
            }

            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $itemSubtotal;

            $itemsData[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'cost_price' => $product->cost_price,
                'subtotal' => $itemSubtotal,
            ];
        }

        $discount = $validated['discount'] ?? 0;
        $total = $subtotal - $discount;
        $changeAmount = $validated['paid_amount'] - $total;

        if ($changeAmount < 0) {
            return response()->json([
                'success' => false,
                'message' => 'จำนวนเงินที่รับไม่เพียงพอ'
            ], 400);
        }

        DB::transaction(function() use ($validated, $itemsData, $subtotal, $discount, $total, $changeAmount) {
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'member_id' => $validated['member_id'],
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $changeAmount,
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                SaleItem::create(array_merge($item, ['sale_id' => $sale->id]));

                $product = Product::find($item['product_id']);
                $stockBefore = $product->stock_quantity;
                $stockAfter = $stockBefore - $item['quantity'];

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'cost_price' => $item['cost_price'],
                    'reference' => $sale->invoice_number,
                    'note' => 'ขายสินค้า',
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            if ($validated['member_id']) {
                Member::find($validated['member_id'])->increment('accumulated_purchase', $total);
            }
        });

        $sale = Sale::with('items.product')->latest()->first();

        return response()->json([
            'success' => true,
            'message' => 'บันทึกการขายเรียบร้อยแล้ว',
            'sale' => $sale,
        ]);
    }

    public function show(Sale $sale)
    {
        $sale->load(['member', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['member', 'user', 'items.product']);
        return view('sales.receipt', compact('sale'));
    }
}
