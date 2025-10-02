@extends('layouts.app')

@section('title', 'Discover Amazing Blogs - ' . config('app.name'))
@section('description', 'Explore our collection of amazing blog posts covering technology, lifestyle, and more.')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Discover Amazing Stories
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Explore our collection of thoughtful articles and insights
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto mb-8">
                    <form action="{{ route('blogs.search') }}" method="GET" class="relative">
                        <input 
                            type="text" 
                            name="q"
                            placeholder="Search for blogs..." 
                            class="w-full px-6 py-4 text-lg rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-white/30"
                        >
                        <button type="submit" class="absolute right-2 top-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-full">
                            Search
                        </button>
                    </form>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div>
                        <div class="text-3xl font-bold">{{ $blogs->total() }}+</div>
                        <div class="text-blue-100">Articles</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $categories->count() }}+</div>
                        <div class="text-blue-100">Categories</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">1K+</div>
                        <div class="text-blue-100">Readers</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Blog Grid -->
                <div class="lg:col-span-3">
                    @if($blogs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($blogs as $blog)
                                <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                    <!-- Blog Image -->
                                    <a href="{{ route('blogs.show', $blog->slug) }}">
                                        @if($blog->cover_image)
                                            <img src="{{ Storage::url($blog->cover_image) }}" 
                                                 alt="{{ $blog->title }}" 
                                                 class="w-full h-48 object-cover">
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                                <span class="text-white text-4xl font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <!-- Blog Content -->
                                    <div class="p-6">
                                        <!-- Category & Date -->
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full">
                                                {{ $blog->category?->name ?? 'Uncategorized' }}
                                            </span>
                                            <time class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $blog->published_at->format('M d, Y') }}
                                            </time>
                                        </div>
                                        
                                        <!-- Title -->
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 hover:text-blue-600 transition-colors">
                                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                                {{ $blog->title }}
                                            </a>
                                        </h3>
                                        
                                        <!-- Excerpt -->
                                        @if($blog->excerpt)
                                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                                {{ $blog->excerpt }}
                                            </p>
                                        @endif
                                        
                                        <!-- Author & Stats -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-sm font-medium">{{ substr($blog->user?->name ?? 'A', 0, 1) }}</span>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $blog->user?->name ?? 'Anonymous' }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                                                <span title="Views">
                                                    👁 {{ $blog->views_count ?? 0 }}
                                                </span>
                                                <span title="Comments">
                                                    💬 {{ $blog->comments_count ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Tags -->
                                        @if($blog->tags && $blog->tags->count() > 0)
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                @foreach($blog->tags->take(3) as $tag)
                                                    <a href="{{ route('blogs.tag', $tag->slug) }}" 
                                                       class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($blogs->hasPages())
                            <div class="mt-8">
                                {{ $blogs->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow">
                            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No blogs found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Be the first to share your thoughts with the community.</p>
                            @auth
                                <a href="{{ route('my-blogs.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                                    Write Your First Blog
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                                    Join Our Community
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Newsletter -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Stay Updated</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Get the latest articles delivered to your inbox</p>
                                <form class="space-y-3">
                                    @csrf
                                    <input type="email" placeholder="Enter your email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                        Subscribe
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Popular Categories -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Popular Categories</h3>
                            <div class="space-y-3">
                                @forelse($categories as $category)
                                    <a href="{{ route('blogs.category', $category->slug) }}" 
                                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ substr($category->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $category->blogs_count }} articles</p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No categories available</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Trending Tags -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Trending Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @forelse($tags->take(15) as $tag)
                                    <a href="{{ route('blogs.tag', $tag->slug) }}" 
                                       class="inline-block px-3 py-1 text-sm bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 dark:bg-gray-700 dark:text-gray-300 rounded-full transition-colors">
                                        #{{ $tag->name }}
                                    </a>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No tags available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection