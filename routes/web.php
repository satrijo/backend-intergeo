<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Models\Article;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $recentArticles = Article::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return Inertia::render('Dashboard', [
        'recentArticles' => $recentArticles,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('articles', ArticleController::class)->middleware(['auth', 'verified']);
Route::resource('categories', CategoryController::class)->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
