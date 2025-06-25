<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::where('user_id', auth()->id())
            ->with('categories')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('articles/Index', [
            'articles' => $articles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('articles/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255',
            'published' => 'boolean',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $article = Article::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'image' => $validated['image'] ?? '',
            'published' => $validated['published'] ?? false,
        ]);

        if (!empty($validated['category_ids'])) {
            $article->categories()->attach($validated['category_ids']);
        }

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['user', 'categories']);
        
        return Inertia::render('articles/Show', [
            'article' => $article,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $article->load('categories');
        
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('articles/Edit', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255',
            'published' => 'boolean',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $article->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'image' => $validated['image'] ?? '',
            'published' => $validated['published'] ?? false,
        ]);

        $article->categories()->sync($validated['category_ids'] ?? []);

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
