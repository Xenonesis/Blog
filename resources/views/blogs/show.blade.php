@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Blog Post -->
            <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                @if($blog->cover_image)
                    <img src="{{ Storage::url($blog->cover_image) }}" alt="{{ $blog->title }}" 
                         class="w-full h-64 object-cover">
                @endif
                
                <div class="p-8">
                    <!-- Meta Information -->
                    <div class="flex items-center mb-6">
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded dark:bg-blue-900 dark:text-blue-300">
                            {{ $blog->category?->name ?? 'Uncategorized' }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm ml-4">
                            Published on {{ $blog->published_at->format('F d, Y') }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm ml-4">
                            {{ $blog->views_count }} views
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $blog->title }}
                    </h1>

                    <!-- Author Info -->
                    <div class="flex items-center mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr($blog->user?->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-900 dark:text-white font-medium">{{ $blog->user?->name ?? 'Unknown Author' }}</p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Author</p>
                            </div>
                        </div>

                        <!-- Edit Button for Owner/Admin -->
                        @auth
                            @if(auth()->id() === $blog->user_id || auth()->user()->isAdmin())
                                <div class="ml-auto">
                                    <a href="{{ route('my-blogs.edit', $blog) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Edit Blog
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none dark:prose-invert mb-8">
                        {!! nl2br(e($blog->content)) !!}
                    </div>

                    <!-- Tags -->
                    @if($blog->tags->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($blog->tags as $tag)
                                    <a href="{{ route('blogs.tag', $tag->slug) }}" 
                                       class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded hover:bg-blue-100 hover:text-blue-800 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-blue-900 dark:hover:text-blue-300">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Like/Dislike Buttons -->
                    @auth
                        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'blog', {{ $blog->id }}, 'like')">
                                @csrf
                                <input type="hidden" name="likeable_type" value="blog">
                                <input type="hidden" name="likeable_id" value="{{ $blog->id }}">
                                <input type="hidden" name="type" value="like">
                                <button type="submit" 
                                        class="flex items-center space-x-2 bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded dark:bg-green-900 dark:text-green-300">
                                    👍
                                    <span id="likes-count">{{ $blog->likes()->where('type', 'like')->count() }}</span>
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'blog', {{ $blog->id }}, 'dislike')">
                                @csrf
                                <input type="hidden" name="likeable_type" value="blog">
                                <input type="hidden" name="likeable_id" value="{{ $blog->id }}">
                                <input type="hidden" name="type" value="dislike">
                                <button type="submit" 
                                        class="flex items-center space-x-2 bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded dark:bg-red-900 dark:text-red-300">
                                    👎
                                    <span id="dislikes-count">{{ $blog->likes()->where('type', 'dislike')->count() }}</span>
                                </button>
                            </form>
                        </div>
                    @endauth

                    <!-- Comments Section -->
                    <div class="mt-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            Comments ({{ $blog->comments()->approved()->count() }})
                        </h3>

                        <!-- Comment Form -->
                        @auth
                            <form action="{{ route('comments.store', $blog) }}" method="POST" class="mb-8">
                                @csrf
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Leave a comment
                                    </label>
                                    <textarea name="content" id="content" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                              placeholder="Share your thoughts..." required></textarea>
                                </div>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Post Comment
                                </button>
                            </form>
                        @else
                            <p class="text-gray-600 dark:text-gray-400 mb-8">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a> to leave a comment.
                            </p>
                        @endauth

                        <!-- Comments List -->
                        @foreach($blog->comments()->approved()->topLevel()->with(['user', 'replies.user'])->get() as $comment)
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</h4>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $comment->content }}</p>
                                        
                                        @auth
                                            <div class="flex items-center space-x-4">
                                                <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'comment', {{ $comment->id }}, 'like')">
                                                    @csrf
                                                    <input type="hidden" name="likeable_type" value="comment">
                                                    <input type="hidden" name="likeable_id" value="{{ $comment->id }}">
                                                    <input type="hidden" name="type" value="like">
                                                    <button type="submit" 
                                                            class="flex items-center space-x-1 text-sm text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400">
                                                        👍
                                                        <span id="comment-likes-{{ $comment->id }}">{{ $comment->likes()->where('type', 'like')->count() }}</span>
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'comment', {{ $comment->id }}, 'dislike')">
                                                    @csrf
                                                    <input type="hidden" name="likeable_type" value="comment">
                                                    <input type="hidden" name="likeable_id" value="{{ $comment->id }}">
                                                    <input type="hidden" name="type" value="dislike">
                                                    <button type="submit" 
                                                            class="flex items-center space-x-1 text-sm text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400">
                                                        👎
                                                        <span id="comment-dislikes-{{ $comment->id }}">{{ $comment->likes()->where('type', 'dislike')->count() }}</span>
                                                    </button>
                                                </form>
                                                
                                                <button onclick="showReplyForm({{ $comment->id }})" 
                                                        class="text-sm text-gray-500 hover:text-blue-600">
                                                    Reply
                                                </button>
                                            </div>

                                            <!-- Reply Form -->
                                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                                <form action="{{ route('comments.store', $blog) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <textarea name="content" rows="3" 
                                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:text-white"
                                                              placeholder="Write a reply..." required></textarea>
                                                    <div class="mt-2 flex space-x-2">
                                                        <button type="submit" 
                                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                            Reply
                                                        </button>
                                                        <button type="button" onclick="hideReplyForm({{ $comment->id }})" 
                                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endauth

                                        <!-- Replies -->
                                        @foreach($comment->replies as $reply)
                                            <div class="mt-4 ml-8 p-3 bg-white dark:bg-gray-600 rounded">
                                                <div class="flex items-start space-x-2">
                                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-bold">{{ substr($reply->user->name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $reply->user->name }}</h5>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $reply->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ $reply->content }}</p>
                                                        
                                                        @auth
                                                            <div class="flex items-center space-x-2">
                                                                <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'comment', {{ $reply->id }}, 'like')">
                                                                    @csrf
                                                                    <input type="hidden" name="likeable_type" value="comment">
                                                                    <input type="hidden" name="likeable_id" value="{{ $reply->id }}">
                                                                    <input type="hidden" name="type" value="like">
                                                                    <button type="submit" 
                                                                            class="flex items-center space-x-1 text-xs text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400">
                                                                        👍
                                                                        <span id="comment-likes-{{ $reply->id }}">{{ $reply->likes()->where('type', 'like')->count() }}</span>
                                                                    </button>
                                                                </form>
                                                                
                                                                <form method="POST" action="{{ route('likes.toggle') }}" class="inline" onsubmit="return handleLikeSubmit(event, 'comment', {{ $reply->id }}, 'dislike')">
                                                                    @csrf
                                                                    <input type="hidden" name="likeable_type" value="comment">
                                                                    <input type="hidden" name="likeable_id" value="{{ $reply->id }}">
                                                                    <input type="hidden" name="type" value="dislike">
                                                                    <button type="submit" 
                                                                            class="flex items-center space-x-1 text-xs text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400">
                                                                        👎
                                                                        <span id="comment-dislikes-{{ $reply->id }}">{{ $reply->likes()->where('type', 'dislike')->count() }}</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            <!-- Related Blogs -->
            @if($relatedBlogs->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Posts</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedBlogs as $relatedBlog)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    @if($relatedBlog->cover_image)
                                        <img src="{{ Storage::url($relatedBlog->cover_image) }}" alt="{{ $relatedBlog->title }}" 
                                             class="w-full h-32 object-cover">
                                    @endif
                                    <div class="p-4">
                                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">
                                            <a href="{{ route('blogs.show', $relatedBlog->slug) }}" class="hover:text-blue-600">
                                                {{ Str::limit($relatedBlog->title, 50) }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $relatedBlog->published_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for interactions -->
    <script>
        function handleLikeSubmit(event, type, id, action) {
            event.preventDefault();
            console.log('Handling like submit:', type, id, action);
            
            const form = event.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update counts
                    if (type === 'blog') {
                        document.getElementById('likes-count').textContent = data.likes_count;
                        document.getElementById('dislikes-count').textContent = data.dislikes_count;
                    } else if (type === 'comment') {
                        document.getElementById('comment-likes-' + id).textContent = data.likes_count;
                        // Also update dislikes if the element exists
                        const dislikesElement = document.getElementById('comment-dislikes-' + id);
                        if (dislikesElement) {
                            dislikesElement.textContent = data.dislikes_count;
                        }
                    }
                } else {
                    console.error('Server returned success=false:', data);
                }
            })
            .catch(error => {
                console.error('Error submitting like:', error);
                alert('Error: ' + error.message);
            });
            
            return false;
        }

        function toggleLike(type, id, action) {
            console.log('Toggling like:', type, id, action);
            
            fetch('{{ route("likes.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    likeable_type: type,
                    likeable_id: id,
                    type: action
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update counts
                    if (type === 'blog') {
                        document.getElementById('likes-count').textContent = data.likes_count;
                        document.getElementById('dislikes-count').textContent = data.dislikes_count;
                    }
                    // For comments, you'd need to update individual comment counts
                } else {
                    console.error('Server returned success=false:', data);
                }
            })
            .catch(error => {
                console.error('Error toggling like:', error);
                alert('Error: ' + error.message);
            });
        }

        function showReplyForm(commentId) {
            document.getElementById('reply-form-' + commentId).classList.remove('hidden');
        }

        function hideReplyForm(commentId) {
            document.getElementById('reply-form-' + commentId).classList.add('hidden');
        }
    </script>
@endsection