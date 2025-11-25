<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\VideoPortfolio;
use Illuminate\Http\Request;

class VideoPortfolioApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = VideoPortfolio::published()
            ->with('user')
            ->ordered()
            ->paginate(10);

        return response()->json($videos);
    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPortfolio $videoPortfolio)
    {
        if ($videoPortfolio->status !== 'published') {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        $videoPortfolio->load('user');

        return response()->json($videoPortfolio);
    }
}
