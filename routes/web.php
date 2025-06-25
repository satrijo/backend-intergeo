<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use App\Models\Portfolio;
use App\Models\ContactInquiry;

Route::get('/', function () {
    return Inertia::render('Home/Index');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('Home/About');
})->name('about');

Route::get('/blog', function () {
    return Inertia::render('Home/Blog');
})->name('blog');

// Public article view route
Route::get('/blog/{article:slug}', function (Article $article) {
    if (!$article->published) {
        abort(404);
    }
    
    $article->load(['user', 'categories']);
    
    return Inertia::render('Home/BlogDetail', [
        'article' => $article,
    ]);
})->name('blog.show');

Route::get('/faq', function () {
    return Inertia::render('Home/FAQ');
})->name('faq');

Route::get('/contact', function () {
    return Inertia::render('Home/Contact');
})->name('contact');

Route::get('/portfolio', function () {
    return Inertia::render('Home/Portfolios');
})->name('portfolios');

// Public portfolio view route
Route::get('/portfolio/{portfolio}', function (Portfolio $portfolio) {
    if ($portfolio->status !== 'published') {
        abort(404);
    }
    $portfolio->load('user');
    return Inertia::render('Home/PortfolioDetail', [
        'portfolio' => $portfolio,
    ]);
})->name('portfolio.show');

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        $recentArticles = Article::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPortfolios = Portfolio::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentInquiries = ContactInquiry::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'recentArticles' => $recentArticles,
            'recentPortfolios' => $recentPortfolios,
            'recentInquiries' => $recentInquiries,
        ]);
    })->name('index');

    Route::resource('articles', ArticleController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('portfolios', PortfolioController::class);
    Route::resource('users', UserController::class);
    Route::resource('contact-inquiries', ContactInquiryController::class);
    // Media upload routes
    Route::post('media/upload', [MediaController::class, 'store'])->name('media.upload');
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::delete('media/{mediaFile}', [MediaController::class, 'destroy'])->name('media.destroy');
});

// Contact form public route
Route::post('/contact', [ContactInquiryController::class, 'store'])->name('contact.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
