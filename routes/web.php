<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PortfolioController;
use App\Models\Article;
use App\Models\Portfolio;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $recentArticles = Article::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $recentPortfolios = Portfolio::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return Inertia::render('Dashboard', [
        'recentArticles' => $recentArticles,
        'recentPortfolios' => $recentPortfolios,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('articles', ArticleController::class)->middleware(['auth', 'verified']);
Route::resource('categories', CategoryController::class)->middleware(['auth', 'verified']);
Route::resource('portfolios', PortfolioController::class)->middleware(['auth', 'verified']);

// Media upload routes
Route::post('media/upload', [MediaController::class, 'store'])->middleware(['auth', 'verified'])->name('media.upload');
Route::get('media', [MediaController::class, 'index'])->middleware(['auth', 'verified'])->name('media.index');
Route::delete('media/{mediaFile}', [MediaController::class, 'destroy'])->middleware(['auth', 'verified'])->name('media.destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
