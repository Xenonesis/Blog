@extends('layouts.app')

@section('title', 'Search Results - ' . config('app.name'))
@section('description', 'Search results for your query.')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Search Header -->
    <section class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Search Results
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Found {{ $blogs->total() }} result{{ $blogs->total() !== 1 ? 's' : '' }} for "<span class="font-medium">{{ $searchTerm }}</span>"
                    </p>
                </div>
                <a href="{{ route('blogs.index') }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Blogs
                </a>
            </div>

            <!-- Search Form -->
            <div class="mt-6 max-w-2xl">
                <form action="{{ route('blogs.search') }}" method="GET" class="flex gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="q"
                            value="{{ $searchTerm }}"
                            placeholder="Search for blogs, topics, or authors..."
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            required
                        >
                    </div>
                    <button type="submit" class="btn-primary px-8">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($blogs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($blogs as $blog)
                        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="block h-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-xl">

                            <!-- Blog Image -->
                            <div class="relative overflow-hidden">
                                @if($blog->cover_image)
                                    <img src="{{ Storage::url($blog->cover_image) }}"
                                         alt="{{ $blog->title }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-primary-400 to-purple-500 flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                    </div>
                                @endif

                                <!-- Reading Time -->
                                <div class="absolute bottom-3 left-3">
                                    <span class="bg-black/70 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $blog->reading_time ?? '5' }} min read
                                    </span>
                                </div>
                            </div>

                            <!-- Blog Content -->
                            <div class="p-6">
                                <!-- Category & Date -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="badge-primary">{{ $blog->category?->name ?? 'Uncategorized' }}</span>
                                    <time class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $blog->published_at->format('M d, Y') }}
                                    </time>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                    {{ $blog->title }}
                                </h3>

                                <!-- Excerpt -->
                                @if($blog->excerpt)
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        {{ $blog->excerpt }}
                                    </p>
                                @endif

                                <!-- Author & Stats -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">{{ substr($blog->user?->name ?? 'A', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $blog->user?->name ?? 'Anonymous' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Author</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $blog->views_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            {{ $blog->comments_count }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Tags -->
                                @if($blog->tags->count() > 0)
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach($blog->tags->take(3) as $tag)
                                            <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($blogs->hasPages())
                    <div class="mt-12">
                        {{ $blogs->links() }}
                    </div>
                @endif
            @else
                <!-- No Results -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No results found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            We couldn't find any blogs matching "<strong>{{ $searchTerm }}</strong>".
                        </p>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Try:</p>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Using different keywords</li>
                                <li>• Checking your spelling</li>
                                <li>• Using more general terms</li>
                            </ul>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('blogs.index') }}" class="btn-primary">
                                Browse All Blogs
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
