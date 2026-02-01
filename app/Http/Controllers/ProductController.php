<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock_quantity', '<=', 'min_stock');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', 0);
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();
        
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $productCode = Product::generateCode();
        return view('products.create', compact('categories', 'productCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:products',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['code'] = Product::generateCode();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        if ($validated['stock_quantity'] > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $validated['stock_quantity'],
                'stock_before' => 0,
                'stock_after' => $validated['stock_quantity'],
                'cost_price' => $validated['cost_price'],
                'note' => 'สต๊อกเริ่มต้น',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $stockMovements = $product->stockMovements()->with('user')->latest()->limit(20)->get();
        return view('products.show', compact('product', 'stockMovements'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'แก้ไขสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%")
                  ->orWhere('barcode', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'code', 'name', 'selling_price', 'stock_quantity', 'unit']);

        return response()->json($products);
    }

    public function getByBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        $product = Product::where('barcode', $barcode)
            ->where('is_active', true)
            ->first(['id', 'code', 'name', 'selling_price', 'stock_quantity', 'unit']);

        if ($product) {
            return response()->json($product);
        }
        return response()->json(['error' => 'ไม่พบสินค้า'], 404);
    }
}
