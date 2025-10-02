<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SAMPLE DATA FROM DATABASE ===\n\n";

// Get a blog with relationships
$blog = \App\Models\Blog::with(['user', 'category', 'tags', 'comments'])->first();

if ($blog) {
    echo "Blog Title: {$blog->title}\n";
    echo "Slug: {$blog->slug}\n";
    echo "Author: {$blog->user->name}\n";
    echo "Category: {$blog->category->name}\n";
    echo "Tags: " . $blog->tags->pluck('name')->join(', ') . "\n";
    echo "Published: {$blog->published_at->format('F d, Y')}\n";
    echo "Status: " . ($blog->is_published ? 'Published' : 'Draft') . "\n";
    echo "Visibility: " . ($blog->is_hidden ? 'Hidden' : 'Visible') . "\n";
    echo "Likes: {$blog->likes_count}\n";
    echo "Dislikes: {$blog->dislikes_count}\n";
    echo "Comments: {$blog->comments_count}\n";
    echo "\nExcerpt: " . substr($blog->excerpt ?? 'No excerpt', 0, 100) . "...\n";
}

echo "\n=== CATEGORIES ===\n";
$categories = \App\Models\Category::withCount('blogs')->get();
foreach ($categories as $category) {
    echo "- {$category->name} ({$category->blogs_count} blogs)\n";
}

echo "\n=== RECENT COMMENTS ===\n";
$comments = \App\Models\Comment::with(['user', 'blog'])->latest()->take(3)->get();
foreach ($comments as $comment) {
    echo "- {$comment->user->name} on '{$comment->blog->title}': " . substr($comment->content, 0, 50) . "...\n";
}

echo "\n=== USERS ===\n";
$users = \App\Models\User::withCount(['blogs', 'comments'])->get();
foreach ($users as $user) {
    echo "- {$user->name} ({$user->role}): {$user->blogs_count} blogs, {$user->comments_count} comments\n";
}

echo "\n✅ All data successfully retrieved from database!\n";
