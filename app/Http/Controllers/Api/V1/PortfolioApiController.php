<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::published()
            ->with('user')
            ->ordered()
            ->paginate(10);

        return response()->json($portfolios);
    }

    /**
     * Display the specified resource.
     */
    public function show(Portfolio $portfolio)
    {
        if ($portfolio->status !== 'published') {
            return response()->json(['message' => 'Portfolio not found.'], 404);
        }

        $portfolio->load('user');

        return response()->json($portfolio);
    }
} 