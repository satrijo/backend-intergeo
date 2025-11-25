<?php

namespace App\Http\Controllers;

use App\Models\VideoPortfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoPortfolioController extends Controller
{
    /**
     * Extract YouTube video ID from various URL formats
     */
    private function extractYouTubeId(string $url): ?string
    {
        // Pattern untuk berbagai format YouTube URL
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Get YouTube thumbnail URL
     */
    private function getYouTubeThumbnail(string $videoId): string
    {
        return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = VideoPortfolio::with('user')
            ->ordered()
            ->paginate(10);

        return Inertia::render('VideoPortfolios/Index', [
            'videos' => $videos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('VideoPortfolios/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Extract YouTube ID
        $youtubeId = $this->extractYouTubeId($request->youtube_url);
        
        if (!$youtubeId) {
            return back()->withErrors([
                'youtube_url' => 'Invalid YouTube URL. Please provide a valid YouTube video URL.'
            ])->withInput();
        }

        $video = VideoPortfolio::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $this->getYouTubeThumbnail($youtubeId),
            'published_at' => $request->published_at,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('dashboard.video-portfolios.index')
            ->with('success', 'Video portfolio created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPortfolio $videoPortfolio)
    {
        $videoPortfolio->load('user');
        
        return Inertia::render('VideoPortfolios/Show', [
            'video' => $videoPortfolio,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoPortfolio $videoPortfolio)
    {
        $data = $videoPortfolio->toArray();
        $data['published_at'] = $videoPortfolio->published_at ? $videoPortfolio->published_at->format('Y-m-d') : null;

        return Inertia::render('VideoPortfolios/Edit', [
            'video' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VideoPortfolio $videoPortfolio)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_url' => 'required|url',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Extract YouTube ID
        $youtubeId = $this->extractYouTubeId($request->youtube_url);
        
        if (!$youtubeId) {
            return back()->withErrors([
                'youtube_url' => 'Invalid YouTube URL. Please provide a valid YouTube video URL.'
            ])->withInput();
        }

        $videoPortfolio->update([
            'title' => $request->title,
            'description' => $request->description,
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $this->getYouTubeThumbnail($youtubeId),
            'published_at' => $request->published_at,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('dashboard.video-portfolios.index')
            ->with('success', 'Video portfolio updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VideoPortfolio $videoPortfolio)
    {
        $videoPortfolio->delete();

        return redirect()->route('dashboard.video-portfolios.index')
            ->with('success', 'Video portfolio deleted successfully.');
    }
}
