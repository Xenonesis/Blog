<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_blogs' => Blog::count(),
            'total_comments' => Comment::count(),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count(),
            'published_blogs' => Blog::where('is_published', true)->count(),
            'hidden_blogs' => Blog::where('is_hidden', true)->count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
        ];

        // Recent activity
        $recent_blogs = Blog::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recent_comments = Comment::with(['user', 'blog'])
            ->latest()
            ->take(5)
            ->get();

        $recent_users = User::latest()
            ->take(5)
            ->get();

        // Monthly stats
        $monthly_blogs = Blog::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('STRFTIME("%Y-%m", created_at) as month')
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(6)
        ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_blogs', 
            'recent_comments', 
            'recent_users',
            'monthly_blogs'
        ));
    }

    public function users()
    {
        $users = User::withCount(['blogs', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully!");
    }

    public function comments()
    {
        $comments = Comment::with(['user', 'blog'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Comment approved successfully!');
    }

    public function rejectComment(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Comment rejected successfully!');
    }

    public function categories()
    {
        $categories = Category::withCount('blogs')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function tags()
    {
        $tags = Tag::withCount('blogs')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }
}
