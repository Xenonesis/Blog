@extends('layouts.app')

@section('title', 'All Categories - ' . config('app.name'))
@section('description', 'Browse all blog categories and discover content that interests you.')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Browse Categories
                </h1>
                <p class="text-xl text-blue-100">
                    Explore {{ $categories->count() }} categories and find topics that interest you
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                        <a href="{{ route('blogs.category', $category->slug) }}" 
                           class="group bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <!-- Category Header -->
                            <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center relative">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors"></div>
                                <div class="relative">
                                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                                        <span class="text-white text-3xl font-bold">{{ substr($category->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Category Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $category->name }}
                                </h3>
                                
                                @if($category->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                        {{ $category->description }}
                                    </p>
                                @endif
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="font-semibold">{{ $category->blogs_count }}</span>
                                        <span class="ml-1">{{ $category->blogs_count === 1 ? 'article' : 'articles' }}</span>
                                    </div>
                                    
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 8 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No categories found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Categories will appear here once they are created.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    @if($categories->count() > 0)
        <section class="py-12 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Category Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-6">
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                {{ $categories->count() }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                Total Categories
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-4xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                                {{ $categories->sum('blogs_count') }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                Total Articles
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-4xl font-bold text-green-600 dark:text-green-400 mb-2">
                                {{ $categories->where('blogs_count', '>', 0)->count() }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                Active Categories
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Popular Categories -->
    @if($categories->where('blogs_count', '>', 0)->count() > 0)
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Most Popular Categories</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories->sortByDesc('blogs_count')->take(6) as $category)
                        @if($category->blogs_count > 0)
                            <a href="{{ route('blogs.category', $category->slug) }}" 
                               class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-2xl font-bold">{{ substr($category->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $category->blogs_count }} {{ $category->blogs_count === 1 ? 'article' : 'articles' }}
                                    </p>
                                </div>
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
