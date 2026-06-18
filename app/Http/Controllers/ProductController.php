<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['user', 'category'])->ordered()->paginate(10);

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => ProductCategory::active()->ordered()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_category_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'price' => 'nullable|numeric|min:0',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'required_with:specifications|string|max:100',
            'specifications.*.value' => 'required_with:specifications|string|max:255',
            'status' => 'required|in:draft,published',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Product::create([
            ...$validated,
            'user_id' => auth()->id(),
            'slug' => $this->uniqueSlug($validated['name']),
            'images' => $validated['images'] ?? [],
            'specifications' => $validated['specifications'] ?? [],
            'featured' => $validated['featured'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category']);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        $product->load('category');

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => ProductCategory::active()->ordered()->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_category_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'price' => 'nullable|numeric|min:0',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'required_with:specifications|string|max:100',
            'specifications.*.value' => 'required_with:specifications|string|max:255',
            'status' => 'required|in:draft,published',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $product->update([
            ...$validated,
            'slug' => $product->name !== $validated['name'] ? $this->uniqueSlug($validated['name'], $product->id) : $product->slug,
            'images' => $validated['images'] ?? [],
            'specifications' => $validated['specifications'] ?? [],
            'featured' => $validated['featured'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
            'published_at' => $validated['status'] === 'published' && !$product->published_at ? now() : $product->published_at,
        ]);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $image) {
            if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function uniqueSlug(string $name, ?string $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
