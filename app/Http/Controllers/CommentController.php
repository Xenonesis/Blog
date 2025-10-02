<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Blog $blog)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'blog_id' => $blog->id,
            'parent_id' => $request->parent_id,
            'status' => 'approved', // Auto-approve for now
        ]);

        // Send notification to blog author
        if ($blog->user_id !== auth()->id()) {
            $blog->user->notify(new \App\Notifications\NewCommentReceived($comment));
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Only allow user to edit their own comments
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update(['content' => $request->content]);

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Only allow user to delete their own comments or admin
        if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
