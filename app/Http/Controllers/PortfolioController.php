<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::with('user')
            ->ordered()
            ->paginate(10);

        return Inertia::render('Portfolios/Index', [
            'portfolios' => $portfolios,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Portfolios/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string|url',
            'client' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'project_date' => 'nullable|date',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:100',
            'status' => 'required|in:draft,published',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $portfolio = Portfolio::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'images' => $request->images ?? [],
            'client' => $request->client,
            'project_url' => $request->project_url,
            'project_date' => $request->project_date,
            'technologies' => $request->technologies ?? [],
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('dashboard.portfolios.index')
            ->with('success', 'Portfolio created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Portfolio $portfolio)
    {
        $portfolio->load('user');
        
        return Inertia::render('Portfolios/Show', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        // Kirim data sebagai array, project_date diformat yyyy-mm-dd
        $data = $portfolio->toArray();
        $data['project_date'] = $portfolio->project_date ? $portfolio->project_date->format('Y-m-d') : null;

        return Inertia::render('Portfolios/Edit', [
            'portfolio' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'string|url',
            'client' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'project_date' => 'nullable|date',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:100',
            'status' => 'required|in:draft,published',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $portfolio->update([
            'title' => $request->title,
            'description' => $request->description,
            'images' => $request->images ?? [],
            'client' => $request->client,
            'project_url' => $request->project_url,
            'project_date' => $request->project_date,
            'technologies' => $request->technologies ?? [],
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('dashboard.portfolios.index')
            ->with('success', 'Portfolio updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        // Delete the image files if they exist
        if ($portfolio->images) {
            foreach ($portfolio->images as $image) {
                if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $portfolio->delete();

        return redirect()->route('dashboard.portfolios.index')
            ->with('success', 'Portfolio deleted successfully.');
    }
}
