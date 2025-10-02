<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Blog Query:\n";
echo str_repeat("=", 50) . "\n\n";

$totalBlogs = App\Models\Blog::count();
echo "Total blogs in database: $totalBlogs\n";

$publishedBlogs = App\Models\Blog::published()->count();
echo "Published blogs: $publishedBlogs\n";

$blogs = App\Models\Blog::published()->take(3)->get();
echo "\nFirst 3 published blogs:\n";
foreach ($blogs as $blog) {
    echo "- ID: {$blog->id}, Title: {$blog->title}\n";
    echo "  Published: " . ($blog->is_published ? 'Yes' : 'No') . "\n";
    echo "  Hidden: " . ($blog->is_hidden ? 'Yes' : 'No') . "\n";
    echo "  User: " . ($blog->user ? $blog->user->name : 'No user') . "\n";
    echo "  Category: " . ($blog->category ? $blog->category->name : 'No category') . "\n";
    echo "\n";
}

$categories = App\Models\Category::withCount('blogs')->get();
echo "Categories: " . $categories->count() . "\n";

$tags = App\Models\Tag::withCount('blogs')->get();
echo "Tags: " . $tags->count() . "\n";
