@extends('layouts.app')

@section('title', 'Discover Amazing Blogs - ' . config('app.name'))
@section('description', 'Explore our collection of amazing blog posts covering technology, lifestyle, and more. Join our vibrant community of writers and readers.')

@push('styles')
<style>
    .masonry-grid {
        column-count: 1;
        column-gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .masonry-grid {
            column-count: 2;
        }
    }
    
    @media (min-width: 1024px) {
        .masonry-grid {
            column-count: 3;
        }
    }
    
    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
    }
    
    .floating-action {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-primary-600 via-purple-600 to-pink-500 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="parallax-bg absolute inset-0 opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 to-purple-600/30"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="hero-title text-5xl lg:text-7xl font-bold text-white mb-6" data-aos="fade-up">
                    Discover Amazing
                    <span class="block bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                        Stories
                    </span>
                </h1>
                <p class="hero-subtitle text-xl lg:text-2xl text-white/90 mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Explore our collection of thoughtful articles, insights, and stories from talented writers around the world.
                </p>
                
                <!-- Enhanced Search Bar -->
                <div class="hero-cta max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Search for blogs, topics, or authors..." 
                            class="w-full pl-12 pr-6 py-4 text-lg rounded-2xl border-0 shadow-xl focus:ring-4 focus:ring-white/30 transition-all duration-300"
                            id="hero-search"
                        >
                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-xl transition-colors">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 max-w-md mx-auto mt-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $totalBlogs }}+</div>
                        <div class="text-white/80">Articles</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $totalAuthors }}+</div>
                        <div class="text-white/80">Authors</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $totalViews }}+</div>
                        <div class="text-white/80">Readers</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 floating-action">
            <div class="w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
        </div>
        <div class="absolute bottom-20 right-10 floating-action" style="animation-delay: 1s;">
            <div class="w-32 h-32 bg-purple-400/20 rounded-full blur-xl"></div>
        </div>
    </section>

    <!-- Featured Blogs Section -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Featured Stories</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">Hand-picked articles that inspire and inform</p>
            </div>
            
            @if($featuredBlogs->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
                    @foreach($featuredBlogs->take(2) as $index => $blog)
                        <article class="card hover-lift group cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="aspect-w-16 aspect-h-9 relative overflow-hidden">
                                @if($blog->cover_image)
                                    <img src="{{ Storage::url($blog->cover_image) }}" alt="{{ $blog->title }}" 
                                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-64 bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <span class="badge-primary mb-2">{{ $blog->category?->name ?? 'Featured' }}</span>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $blog->title }}</h3>
                                    <p class="text-white/90 text-sm">{{ Str::limit($blog->excerpt, 100) }}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center space-x-4">
                                        <span>{{ $blog->user?->name }}</span>
                                        <span>{{ $blog->published_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span>{{ $blog->views_count }} views</span>
                                        <span>{{ $blog->comments_count }} comments</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Filter Tabs -->
    <section class="py-8 bg-gray-50 dark:bg-gray-800 border-y border-gray-200 dark:border-gray-700 sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-2" x-data="{ activeFilter: 'all' }">
                    <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'btn-primary' : 'btn-secondary'" class="text-sm">
                        All Posts
                    </button>
                    <button @click="activeFilter = 'latest'" :class="activeFilter === 'latest' ? 'btn-primary' : 'btn-secondary'" class="text-sm">
                        Latest
                    </button>
                    <button @click="activeFilter = 'popular'" :class="activeFilter === 'popular' ? 'btn-primary' : 'btn-secondary'" class="text-sm">
                        Popular
                    </button>
                    <button @click="activeFilter = 'trending'" :class="activeFilter === 'trending' ? 'btn-primary' : 'btn-secondary'" class="text-sm">
                        Trending
                    </button>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600 dark:text-gray-400">View:</label>
                        <button id="grid-view" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button id="list-view" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Blog Grid -->
                <div class="lg:col-span-3">
                    <div id="blog-grid" class="blog-grid masonry-grid">
                        @forelse($blogs as $index => $blog)
                            <article class="blog-card masonry-item card hover-lift group cursor-pointer" 
                                     data-aos="fade-up" 
                                     data-aos-delay="{{ ($index % 6) * 100 }}"
                                     onclick="window.location.href='{{ route('blogs.show', $blog->slug) }}'">
                                
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
                                    
                                    <!-- Bookmark Button -->
                                    @auth
                                        <button onclick="event.stopPropagation(); toggleBookmark({{ $blog->id }})" 
                                                id="bookmark-{{ $blog->id }}"
                                                class="absolute top-3 right-3 p-2 rounded-full bg-white/90 hover:bg-white shadow-lg transition-all duration-200 hover:scale-110">
                                            <svg class="w-5 h-5 text-gray-400 hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                        </button>
                                    @endauth
                                    
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
                            </article>
                        @empty
                            <div class="col-span-full text-center py-16">
                                <div class="max-w-md mx-auto">
                                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No blogs found</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Be the first to share your thoughts with the community.</p>
                                    @auth
                                        <a href="{{ route('my-blogs.create') }}" class="btn-primary">
                                            Write Your First Blog
                                        </a>
                                    @else
                                        <a href="{{ route('register') }}" class="btn-primary">
                                            Join Our Community
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Load More Trigger -->
                    <div id="load-more-trigger" class="text-center py-8">
                        <div class="loading-spinner mx-auto mb-4"></div>
                        <p class="text-gray-500 dark:text-gray-400">Loading more articles...</p>
                    </div>
                    
                    <!-- Pagination -->
                    @if($blogs->hasPages())
                        <div class="mt-12">
                            {{ $blogs->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Newsletter Subscribe -->
                    <div class="card" data-aos="fade-up">
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Stay Updated</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Get the latest articles delivered to your inbox</p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Enter your email" class="input-modern text-sm">
                                <button type="submit" class="btn-primary w-full text-sm">Subscribe</button>
                            </form>
                        </div>
                    </div>

                    <!-- Popular Categories -->
                    <div class="card" data-aos="fade-up" data-aos-delay="100">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Popular Categories</h3>
                            <div class="space-y-3">
                                @forelse($categories as $category)
                                    <a href="{{ route('blogs.category', $category->slug) }}" 
                                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-purple-500 rounded-lg flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ substr($category->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ $category->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $category->blogs_count }} articles</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No categories available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const blogGrid = document.getElementById('blog-grid');
    
    if (gridView && listView && blogGrid) {
        gridView.addEventListener('click', () => {
            blogGrid.className = 'blog-grid masonry-grid';
            gridView.classList.add('bg-primary-100');
            listView.classList.remove('bg-primary-100');
        });
        
        listView.addEventListener('click', () => {
            blogGrid.className = 'blog-grid space-y-6';
            listView.classList.add('bg-primary-100');
            gridView.classList.remove('bg-primary-100');
        });
    }
});
</script>
@endpush
@endsection