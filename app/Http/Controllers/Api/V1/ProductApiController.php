<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->integer('per_page', 10), 50);

        $products = Product::with('category')
            ->published()
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($category) => $category->where('slug', $request->category));
            })
            ->when($request->boolean('featured'), fn ($query) => $query->featured())
            ->ordered()
            ->paginate($perPage);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        if ($product->status !== 'published') {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->load('category');

        return response()->json($product);
    }
}
