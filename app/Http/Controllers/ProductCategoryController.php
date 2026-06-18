<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->ordered()->get();

        return Inertia::render('ProductCategories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('ProductCategories/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ProductCategory::create([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('dashboard.product-categories.index')
            ->with('success', 'Product category created successfully.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return Inertia::render('ProductCategories/Edit', [
            'category' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $productCategory->update([
            ...$validated,
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('dashboard.product-categories.index')
            ->with('success', 'Product category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->route('dashboard.product-categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}
