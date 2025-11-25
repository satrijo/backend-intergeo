<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoPortfolioController;
use App\Models\Article;
use App\Models\Portfolio;
use App\Models\ContactInquiry;
use App\Models\VideoPortfolio;

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

// Public work showcase routes
Route::get('/work-showcase', function () {
    return Inertia::render('Home/WorkShowcase');
})->name('work-showcase');

// Public work showcase detail route
Route::get('/work-showcase/{videoPortfolio}', function (VideoPortfolio $videoPortfolio) {
    if ($videoPortfolio->status !== 'published') {
        abort(404);
    }
    $videoPortfolio->load('user');
    return Inertia::render('Home/WorkShowcaseDetail', [
        'video' => $videoPortfolio,
    ]);
})->name('work-showcase.show');

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        $recentArticles = Article::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPortfolios = Portfolio::with('user')
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
    Route::resource('video-portfolios', VideoPortfolioController::class);
    Route::resource('users', UserController::class);
    Route::resource('contact-inquiries', ContactInquiryController::class);
    // Media upload routes
    Route::post('media/upload', [MediaController::class, 'store'])->name('media.upload');
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::delete('media/{mediaFile}', [MediaController::class, 'destroy'])->name('media.destroy');
});

// Contact form public route
Route::post('/contact', [ContactInquiryController::class, 'store'])->name('contact.store');

// Sitemap route
Route::get('/sitemap.xml', function () {
    $articles = \App\Models\Article::where('published', true)->get();
    $portfolios = \App\Models\Portfolio::where('status', 'published')->get();
    
    $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>daily</changefreq>' . "\n";
    $content .= '    <priority>1.0</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // About page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/about</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>monthly</changefreq>' . "\n";
    $content .= '    <priority>0.8</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // Services page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/services</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>monthly</changefreq>' . "\n";
    $content .= '    <priority>0.8</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // Portfolio page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/portfolio</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>weekly</changefreq>' . "\n";
    $content .= '    <priority>0.8</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // Blog page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/blog</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>weekly</changefreq>' . "\n";
    $content .= '    <priority>0.8</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // Contact page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/contact</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>monthly</changefreq>' . "\n";
    $content .= '    <priority>0.7</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // FAQ page
    $content .= '  <url>' . "\n";
    $content .= '    <loc>https://surveyseismikgpr.com/faq</loc>' . "\n";
    $content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $content .= '    <changefreq>monthly</changefreq>' . "\n";
    $content .= '    <priority>0.6</priority>' . "\n";
    $content .= '  </url>' . "\n";
    
    // Articles
    foreach ($articles as $article) {
        $content .= '  <url>' . "\n";
        $content .= '    <loc>https://surveyseismikgpr.com/blog/' . $article->slug . '</loc>' . "\n";
        $content .= '    <lastmod>' . $article->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
        $content .= '    <changefreq>monthly</changefreq>' . "\n";
        $content .= '    <priority>0.7</priority>' . "\n";
        $content .= '  </url>' . "\n";
    }
    
    // Portfolios
    foreach ($portfolios as $portfolio) {
        $content .= '  <url>' . "\n";
        $content .= '    <loc>https://surveyseismikgpr.com/portfolio/' . $portfolio->id . '</loc>' . "\n";
        $content .= '    <lastmod>' . $portfolio->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
        $content .= '    <changefreq>monthly</changefreq>' . "\n";
        $content .= '    <priority>0.6</priority>' . "\n";
        $content .= '  </url>' . "\n";
    }
    
    $content .= '</urlset>';
    
    return response($content, 200, [
        'Content-Type' => 'application/xml',
        'Cache-Control' => 'public, max-age=3600'
    ]);
})->name('sitemap.xml');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
