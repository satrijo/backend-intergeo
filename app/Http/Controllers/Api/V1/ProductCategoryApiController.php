<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;

class ProductCategoryApiController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::active()
            ->withCount(['products' => fn ($query) => $query->published()])
            ->ordered()
            ->get();

        return response()->json($categories);
    }

    public function show(ProductCategory $productCategory)
    {
        if (!$productCategory->is_active) {
            return response()->json(['message' => 'Product category not found.'], 404);
        }

        $productCategory->load(['products' => fn ($query) => $query->published()->ordered()]);

        return response()->json($productCategory);
    }
}
