<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource (Homepage/Blog listing).
     */
    public function index(Request $request)
    {
        $query = Blog::with(['user', 'category', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Tag filter
        if ($request->has('tag') && $request->tag) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $blogs = $query->paginate(12);
        $categories = Category::withCount('blogs')->get();
        $tags = Tag::withCount('blogs')->get();

        return view('blogs.index', compact('blogs', 'categories', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('blogs.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $blog = new Blog($request->all());
        $blog->user_id = auth()->id();
        $blog->slug = Str::slug($request->title);
        
        if ($request->hasFile('cover_image')) {
            $blog->cover_image = $request->file('cover_image')->store('blog-covers', 'public');
        }

        $blog->save();

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('blogs.show', $blog->slug)
            ->with('success', 'Blog created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        // Check if blog is published and not hidden (unless user is admin or owner)
        if ((!$blog->is_published || $blog->is_hidden) && 
            (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $blog->user_id))) {
            abort(404);
        }

        $blog->load(['user', 'category', 'tags', 'comments.user', 'comments.replies.user']);
        
        // Increment view count
        $blog->increment('views_count');

        // Get related blogs
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category_id', $blog->category_id)
            ->limit(3)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        // Check if user can edit this blog
        if (auth()->id() !== $blog->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('blogs.edit', compact('blog', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        // Check if user can edit this blog
        if (auth()->id() !== $blog->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
        ]);

        $blog->fill($request->all());
        $blog->slug = Str::slug($request->title);
        
        if ($request->hasFile('cover_image')) {
            $blog->cover_image = $request->file('cover_image')->store('blog-covers', 'public');
        }

        $blog->save();

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('blogs.show', $blog->slug)
            ->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Only admin can delete blogs
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'Blog deleted successfully!');
    }

    /**
     * Show all categories.
     */
    public function categories()
    {
        $categories = Category::withCount('blogs')->get();
        return view('blogs.categories', compact('categories'));
    }

    /**
     * Show blogs by category.
     */
    public function category(Category $category)
    {
        $blogs = Blog::with(['user', 'category', 'tags'])
            ->published()
            ->where('category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blogs.category', compact('blogs', 'category'));
    }

    /**
     * Show blogs by tag.
     */
    public function tag(Tag $tag)
    {
        $blogs = Blog::with(['user', 'category', 'tags'])
            ->published()
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('tag_id', $tag->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blogs.tag', compact('blogs', 'tag'));
    }

    /**
     * Search blogs.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q');
        
        $blogs = Blog::with(['user', 'category', 'tags'])
            ->published()
            ->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('content', 'like', "%{$searchTerm}%")
                      ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blogs.search', compact('blogs', 'searchTerm'));
    }
}
