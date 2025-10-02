@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))
@section('description', $category->description ?? 'Browse ' . $category->name . ' articles')

@section('content')
<div class="min-h-screen">
    <!-- Category Header -->
    <section class="relative bg-gradient-to-br from-primary-600 via-purple-600 to-pink-500 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-6">
                    <span class="text-4xl font-bold text-white">{{ substr($category->name, 0, 1) }}</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xl text-white/90 max-w-2xl mx-auto">
                        {{ $category->description }}
                    </p>
                @endif
                <div class="mt-6">
                    <span class="text-white/80 text-lg">
                        {{ $blogs->total() }} {{ Str::plural('article', $blogs->total()) }} found
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Link -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Home
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($blogs as $blog)
                    <article class="card hover-lift group cursor-pointer" onclick="window.location.href='{{ route('blogs.show', $blog->slug) }}'">
                        <!-- Blog Image -->
                        <div class="relative overflow-hidden">
                            @if($blog->cover_image)
                                <img src="{{ Storage::url($blog->cover_image) }}" 
                                     alt="{{ $blog->title }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-primary-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-black/70 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $blog->reading_time ?? '5' }} min read
                                </span>
                            </div>
                        </div>
                        
                        <!-- Blog Content -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge-primary">{{ $blog->category?->name }}</span>
                                <time class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $blog->published_at->format('M d, Y') }}
                                </time>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                {{ $blog->title }}
                            </h3>
                            
                            @if($blog->excerpt)
                                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                    {{ $blog->excerpt }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">{{ substr($blog->user?->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $blog->user?->name }}</p>
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
                    </article>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-md mx-auto">
                            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No blogs found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">There are no blogs in this category yet.</p>
                            <a href="{{ route('home') }}" class="btn-primary">
                                Browse All Blogs
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="mt-12">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
