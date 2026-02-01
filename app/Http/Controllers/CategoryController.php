<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'แก้ไขหมวดหมู่เรียบร้อยแล้ว');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'ไม่สามารถลบหมวดหมู่ที่มีสินค้าอยู่ได้');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}
