<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleApiController;
use App\Http\Controllers\Api\V1\PortfolioApiController;
use App\Http\Controllers\Api\V1\CategoryApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Version 1
Route::prefix('v1')->name('api.v1.')->group(function () {
    // Article Endpoints
    Route::apiResource('articles', ArticleApiController::class)->only(['index', 'show']);

    // Portfolio Endpoints
    Route::get('portfolios/stats', [PortfolioApiController::class, 'stats']);
    Route::apiResource('portfolios', PortfolioApiController::class)->only(['index', 'show']);

    // Category Endpoints
    Route::apiResource('categories', CategoryApiController::class)->only(['index', 'show']);
}); 