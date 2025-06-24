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

    /**
     * Display portfolio statistics.
     */
    public function stats()
    {
        $published = Portfolio::published();
        $totalProjects = $published->count();
        $firstProject = Portfolio::published()->orderBy('project_date', 'asc')->first();
        $lastProject = Portfolio::published()->orderBy('project_date', 'desc')->first();
        $earliestYear = $firstProject && $firstProject->project_date ? (int)date('Y', strtotime($firstProject->project_date)) : null;
        $latestYear = $lastProject && $lastProject->project_date ? (int)date('Y', strtotime($lastProject->project_date)) : null;
        $currentYear = (int)date('Y');
        $yearsExperience = $earliestYear ? max(1, $currentYear - $earliestYear + 1) : 0;
        $uniqueClients = Portfolio::published()->whereNotNull('client')->distinct('client')->count('client');
        $allTechnologies = Portfolio::published()->pluck('technologies')->flatten()->unique()->filter()->count();

        return response()->json([
            'total_projects' => $totalProjects,
            'years_experience' => $yearsExperience,
            'unique_clients' => $uniqueClients,
            'unique_technologies' => $allTechnologies,
            'earliest_year' => $earliestYear,
            'latest_year' => $latestYear,
        ]);
    }
} 