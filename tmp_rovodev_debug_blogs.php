<?php

use Illuminate\Foundation\Application;

require_once 'vendor/autoload.php';

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Debug blog data
echo "=== Blog Debug Information ===\n";

try {
    $totalBlogs = App\Models\Blog::count();
    echo "Total blogs in database: $totalBlogs\n";
    
    if ($totalBlogs > 0) {
        $blogs = App\Models\Blog::all();
        foreach ($blogs as $blog) {
            echo "Blog ID: {$blog->id}\n";
            echo "  Title: {$blog->title}\n";
            echo "  Published: " . ($blog->is_published ? 'Yes' : 'No') . "\n";
            echo "  Hidden: " . ($blog->is_hidden ? 'Yes' : 'No') . "\n";
            echo "  Published At: " . ($blog->published_at ? $blog->published_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
            echo "  Status: " . ($blog->status ?? 'NULL') . "\n";
            echo "  ---\n";
        }
        
        $publishedQuery = App\Models\Blog::published();
        echo "\nPublished query SQL: " . $publishedQuery->toSql() . "\n";
        
        $publishedBlogs = $publishedQuery->get();
        echo "Published blogs count: " . $publishedBlogs->count() . "\n";
        
    } else {
        echo "No blogs found in database. Creating sample data...\n";
        
        // Create a test user if none exists
        $user = App\Models\User::first();
        if (!$user) {
            $user = App\Models\User::create([
                'name' => 'Test Author',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        // Create a test category if none exists
        $category = App\Models\Category::first();
        if (!$category) {
            $category = App\Models\Category::create([
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Technology related posts',
            ]);
        }
        
        // Create test blogs
        for ($i = 1; $i <= 3; $i++) {
            App\Models\Blog::create([
                'title' => "Sample Blog Post $i",
                'slug' => "sample-blog-post-$i",
                'excerpt' => "This is a sample excerpt for blog post $i",
                'content' => "<p>This is the content of sample blog post $i. It contains some sample text to demonstrate the blog functionality.</p>",
                'user_id' => $user->id,
                'category_id' => $category->id,
                'is_published' => true,
                'is_hidden' => false,
                'published_at' => now(),
            ]);
        }
        
        echo "Created 3 sample blog posts.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== End Debug ===\n";