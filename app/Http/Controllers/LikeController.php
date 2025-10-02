<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like/dislike for a blog or comment.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|in:blog,comment',
            'likeable_id' => 'required|integer',
            'type' => 'required|in:like,dislike',
        ]);

        $likeableType = $request->likeable_type === 'blog' ? 'App\Models\Blog' : 'App\Models\Comment';
        
        // Find existing like/dislike by this user
        $existingLike = Like::where([
            'user_id' => auth()->id(),
            'likeable_type' => $likeableType,
            'likeable_id' => $request->likeable_id,
        ])->first();

        if ($existingLike) {
            if ($existingLike->type === $request->type) {
                // Remove like/dislike if clicking the same button
                $existingLike->delete();
            } else {
                // Change like to dislike or vice versa
                $existingLike->update(['type' => $request->type]);
            }
        } else {
            // Create new like/dislike
            Like::create([
                'user_id' => auth()->id(),
                'likeable_type' => $likeableType,
                'likeable_id' => $request->likeable_id,
                'type' => $request->type,
            ]);
        }

        // Get updated counts
        $likesCount = Like::where([
            'likeable_type' => $likeableType,
            'likeable_id' => $request->likeable_id,
            'type' => 'like',
        ])->count();

        $dislikesCount = Like::where([
            'likeable_type' => $likeableType,
            'likeable_id' => $request->likeable_id,
            'type' => 'dislike',
        ])->count();

        return response()->json([
            'success' => true,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);
    }
}
