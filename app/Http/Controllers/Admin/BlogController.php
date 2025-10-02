<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['user', 'category', 'tags']);

        // Advanced Filtering
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true)->where('is_hidden', false);
            } elseif ($request->status === 'hidden') {
                $query->where('is_hidden', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            } elseif ($request->status === 'scheduled') {
                $query->where('status', 'scheduled');
            }
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $blogs = $query->paginate(20)->appends($request->query());

        // Data for filters
        $categories = Category::all();
        $authors = User::all();

        return view('admin.blogs.index', compact('blogs', 'categories', 'authors'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blogs.create', compact('categories', 'tags'));
    }

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
            'is_hidden' => 'boolean',
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

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog created successfully!');
    }

    public function show(Blog $blog)
    {
        $blog->load(['user', 'category', 'tags', 'comments.user']);
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.blogs.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, Blog $blog)
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
            'is_hidden' => 'boolean',
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

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully!');
    }

    public function toggleVisibility(Blog $blog)
    {
        $blog->update(['is_hidden' => !$blog->is_hidden]);
        
        $status = $blog->is_hidden ? 'hidden' : 'visible';
        return redirect()->back()->with('success', "Blog marked as {$status}!");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,hide,show,delete',
            'blog_ids' => 'required|array',
            'blog_ids.*' => 'exists:blogs,id',
        ]);

        $blogIds = $request->blog_ids;
        $action = $request->action;
        $count = 0;

        switch ($action) {
            case 'publish':
                $count = Blog::whereIn('id', $blogIds)->update([
                    'is_published' => true,
                    'status' => 'published',
                    'published_at' => now()
                ]);
                break;

            case 'unpublish':
                $count = Blog::whereIn('id', $blogIds)->update([
                    'is_published' => false,
                    'status' => 'draft'
                ]);
                break;

            case 'hide':
                $count = Blog::whereIn('id', $blogIds)->update(['is_hidden' => true]);
                break;

            case 'show':
                $count = Blog::whereIn('id', $blogIds)->update(['is_hidden' => false]);
                break;

            case 'delete':
                $count = Blog::whereIn('id', $blogIds)->delete();
                break;
        }

        return redirect()->back()->with('success', "Bulk action '{$action}' applied to {$count} blog(s)!");
    }

    public function seoAnalysis(Blog $blog)
    {
        $seoData = $blog->generateSeoScore();
        $readingTime = $blog->calculateReadingTime();

        return response()->json([
            'seo_score' => $seoData,
            'reading_time' => $readingTime,
        ]);
    }

    public function schedulePublish(Request $request, Blog $blog)
    {
        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'auto_publish' => 'boolean',
        ]);

        $blog->update([
            'scheduled_at' => $request->scheduled_at,
            'auto_publish' => $request->auto_publish ?? true,
            'status' => 'scheduled',
            'is_published' => false,
        ]);

        return redirect()->back()->with('success', 'Blog scheduled for publishing!');
    }
}
