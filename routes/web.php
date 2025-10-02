<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/category/{category:slug}', [BlogController::class, 'category'])->name('blogs.category');
Route::get('/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blogs.tag');
Route::get('/search', [BlogController::class, 'search'])->name('blogs.search');

// Authentication routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User blog management
    Route::resource('my-blogs', BlogController::class)->except(['index', 'show']);
    
    // Comments
    Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Likes
    Route::post('/like', [LikeController::class, 'toggle'])->name('likes.toggle');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Blog Management
    Route::resource('blogs', AdminBlogController::class);
    Route::patch('/blogs/{blog}/toggle-visibility', [AdminBlogController::class, 'toggleVisibility'])->name('blogs.toggle-visibility');
    Route::post('/blogs/bulk-action', [AdminBlogController::class, 'bulkAction'])->name('blogs.bulk-action');
    Route::get('/blogs/{blog}/seo-analysis', [AdminBlogController::class, 'seoAnalysis'])->name('blogs.seo-analysis');
    Route::post('/blogs/{blog}/schedule', [AdminBlogController::class, 'schedulePublish'])->name('blogs.schedule');
    
    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/blog/{blog}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'blogDetails'])->name('analytics.blog');
    Route::get('/analytics/export', [\App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
    
    // Comment Management
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments.index');
    Route::patch('/comments/{comment}/approve', [AdminController::class, 'approveComment'])->name('comments.approve');
    Route::patch('/comments/{comment}/reject', [AdminController::class, 'rejectComment'])->name('comments.reject');
    
    // Category Management
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    
    // Tag Management
    Route::get('/tags', [AdminController::class, 'tags'])->name('tags.index');
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);
});

require __DIR__.'/auth.php';
