<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Blog Index</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug-info { background: #f0f0f0; padding: 15px; margin-bottom: 20px; border: 1px solid #ccc; }
        .blog-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Debug Page - Blog Listing</h1>
    
    <div class="debug-info">
        <h2>Debug Information:</h2>
        <p>Total Blogs: {{ $blogs->total() }}</p>
        <p>Current Page: {{ $blogs->currentPage() }}</p>
        <p>Per Page: {{ $blogs->perPage() }}</p>
        <p>Categories Count: {{ $categories->count() }}</p>
        <p>Tags Count: {{ $tags->count() }}</p>
    </div>

    <h2>Blog Listings:</h2>
    
    @forelse($blogs as $blog)
        <div class="blog-card">
            <h3>{{ $blog->title }}</h3>
            <p><strong>ID:</strong> {{ $blog->id }}</p>
            <p><strong>Category:</strong> {{ $blog->category?->name ?? 'N/A' }}</p>
            <p><strong>Author:</strong> {{ $blog->user?->name ?? 'N/A' }}</p>
            <p><strong>Published:</strong> {{ $blog->is_published ? 'Yes' : 'No' }}</p>
            <p><strong>Hidden:</strong> {{ $blog->is_hidden ? 'Yes' : 'No' }}</p>
            <p><strong>Excerpt:</strong> {{ Str::limit($blog->excerpt ?? '', 100) }}</p>
            @if($blog->cover_image)
                <p><strong>Cover Image:</strong> {{ $blog->cover_image }}</p>
                <img src="{{ Storage::url($blog->cover_image) }}" alt="{{ $blog->title }}" style="max-width: 200px;">
            @else
                <p><strong>Cover Image:</strong> None</p>
            @endif
        </div>
    @empty
        <p style="color: red; font-weight: bold;">NO BLOGS FOUND!</p>
    @endforelse

    <hr>
    <h2>Categories:</h2>
    <ul>
        @foreach($categories as $category)
            <li>{{ $category->name }} ({{ $category->blogs_count }} blogs)</li>
        @endforeach
    </ul>

    <h2>Tags:</h2>
    <ul>
        @foreach($tags->take(10) as $tag)
            <li>{{ $tag->name }} ({{ $tag->blogs_count }} blogs)</li>
        @endforeach
    </ul>
</body>
</html>
