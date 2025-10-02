@extends('layouts.app')

@section('title', 'Discover Amazing Stories - ' . config('app.name'))
@section('description', 'Explore our collection of amazing blog posts covering technology, lifestyle, and more.')

@section('content')
<!-- Modern Hero Section with Animated Background -->
<section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary-600 via-purple-600 to-pink-600">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white/10 rounded-full blur-3xl -top-48 -left-48 animate-pulse"></div>
        <div class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-3xl top-1/2 right-0 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute w-96 h-96 bg-pink-500/10 rounded-full blur-3xl bottom-0 left-1/3 animate-pulse" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <div class="space-y-8" data-aos="fade-up">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-white text-sm font-medium">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                {{ $blogs->total() }}+ Articles Available
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold text-white leading-tight hero-title">
                Discover Stories<br/>
                <span class="bg-gradient-to-r from-yellow-200 via-pink-200 to-purple-200 bg-clip-text text-transparent">
                    That Inspire
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto hero-subtitle">
                Explore thoughtful articles, expert insights, and creative perspectives from our vibrant community of writers
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto hero-cta" data-aos="fade-up" data-aos-delay="200">
                <form action="{{ route('blogs.search') }}" method="GET" class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 via-pink-400 to-purple-400 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                    <div class="relative flex items-center bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="pl-6">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="q"
                            placeholder="Search for articles, topics, or authors..." 
                            class="flex-1 px-4 py-5 text-lg bg-transparent border-none focus:outline-none focus:ring-0 text-gray-900 dark:text-white placeholder-gray-400"
                        >
                        <button type="submit" class="m-2 px-8 py-3 bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-lg">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4" data-aos="fade-up" data-aos-delay="300">
                @auth
                    <a href="{{ route('my-blogs.create') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Write Your Story
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary bg-white/20 backdrop-blur-md border-white/30 text-white hover:bg-white/30">
                        Sign In
                    </a>
                @endauth
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto pt-12" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ $blogs->total() }}+</div>
                    <div class="text-white/80 text-sm md:text-base">Published Articles</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">{{ $categories->count() }}+</div>
                    <div class="text-white/80 text-sm md:text-base">Categories</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-white mb-2">10K+</div>
                    <div class="text-white/80 text-sm md:text-base">Active Readers</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</section>

<!-- Featured Categories Section -->
<section class="py-20 bg-gray-50 dark:bg-gray-900 section-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Explore by Category</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">Find content that matches your interests</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories->take(6) as $index => $category)
                <a href="{{ route('blogs.category', $category->slug) }}" 
                   class="group relative overflow-hidden rounded-2xl p-6 bg-white dark:bg-gray-800 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2"
                   data-aos="fade-up" 
                   data-aos-delay="{{ $index * 50 }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg group-hover:scale-110 transition-transform duration-500">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <h3 class="text-center font-semibold text-gray-900 dark:text-white mb-1">{{ $category->name }}</h3>
                        <p class="text-center text-sm text-gray-500 dark:text-gray-400">{{ $category->blogs_count }} articles</p>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if($categories->count() > 6)
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('blogs.categories') }}" class="btn-secondary">
                    View All Categories
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Latest Articles Section -->
<section class="py-20 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12" data-aos="fade-up">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-2">Latest Articles</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">Fresh perspectives and insights</p>
            </div>
        </div>
        
        @if($blogs->count() > 0)
            <!-- Featured Blog (First One) -->
            @php $featuredBlog = $blogs->first(); @endphp
            <div class="mb-12" data-aos="fade-up">
                <article class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 shadow-xl hover:shadow-2xl transition-all duration-500">
                    <div class="grid md:grid-cols-2 gap-8 p-8 md:p-12">
                        <div class="relative overflow-hidden rounded-2xl">
                            <a href="{{ route('blogs.show', $featuredBlog->slug) }}">
                                @if($featuredBlog->cover_image)
                                    <img src="{{ Storage::url($featuredBlog->cover_image) }}" 
                                         alt="{{ $featuredBlog->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full min-h-[400px] bg-gradient-to-br from-primary-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <span class="text-white text-8xl font-bold">{{ substr($featuredBlog->title, 0, 1) }}</span>
                                    </div>
                                @endif
                            </a>
                        </div>
                        
                        <div class="flex flex-col justify-center">
                            <div class="inline-flex items-center px-4 py-2 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full text-sm font-semibold mb-4 w-fit">
                                ⭐ Featured Article
                            </div>
                            
                            <a href="{{ route('blogs.category', $featuredBlog->category->slug) }}" 
                               class="inline-flex items-center text-primary-600 dark:text-primary-400 font-semibold mb-4 w-fit hover:underline">
                                {{ $featuredBlog->category?->name ?? 'Uncategorized' }}
                            </a>
                            
                            <h3 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                <a href="{{ route('blogs.show', $featuredBlog->slug) }}">
                                    {{ $featuredBlog->title }}
                                </a>
                            </h3>
                            
                            @if($featuredBlog->excerpt)
                                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6 line-clamp-3">
                                    {{ $featuredBlog->excerpt }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold">{{ substr($featuredBlog->user?->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $featuredBlog->user?->name ?? 'Anonymous' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $featuredBlog->published_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('blogs.show', $featuredBlog->slug) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-300 hover:shadow-lg">
                                    Read More
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            
            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 blog-grid">
                @foreach($blogs->skip(1) as $index => $blog)
                    <article class="group card-featured blog-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="block relative overflow-hidden">
                            @if($blog->cover_image)
                                <img src="{{ Storage::url($blog->cover_image) }}" 
                                     alt="{{ $blog->title }}" 
                                     class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-56 bg-gradient-to-br from-primary-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                    <span class="text-white text-5xl font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full text-sm font-semibold text-gray-900 dark:text-white shadow-lg">
                                {{ $blog->category?->name ?? 'Uncategorized' }}
                            </div>
                        </a>
                        
                        <div class="p-6">
                            <time class="text-sm text-gray-500 dark:text-gray-400 mb-3 block">
                                {{ $blog->published_at->format('M d, Y') }} · {{ $blog->read_time ?? '5' }} min read
                            </time>
                            
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                <a href="{{ route('blogs.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h3>
                            
                            @if($blog->excerpt)
                                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ $blog->excerpt }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ substr($blog->user?->name ?? 'A', 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $blog->user?->name ?? 'Anonymous' }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $blog->views_count ?? 0 }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        {{ $blog->comments_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($blog->tags && $blog->tags->count() > 0)
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($blog->tags->take(3) as $tag)
                                        <a href="{{ route('blogs.tag', $tag->slug) }}" 
                                           class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
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
                <div class="mt-16" data-aos="fade-up">
                    {{ $blogs->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No Articles Yet</h3>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Be the first to share your thoughts and inspire others with your unique perspective.
                </p>
                @auth
                    <a href="{{ route('my-blogs.create') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Write Your First Article
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">
                        Join Our Community
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-gradient-to-br from-primary-600 via-purple-600 to-pink-600 relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute w-96 h-96 bg-white/10 rounded-full blur-3xl top-0 right-0"></div>
        <div class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-3xl bottom-0 left-0"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
        <div class="w-20 h-20 mx-auto mb-6 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Never Miss an Update</h2>
        <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
            Subscribe to our newsletter and get the latest articles, insights, and updates delivered straight to your inbox.
        </p>
        
        <form class="max-w-md mx-auto" data-aos="fade-up" data-aos-delay="200">
            @csrf
            <div class="flex flex-col sm:flex-row gap-4">
                <input 
                    type="email" 
                    placeholder="Enter your email address" 
                    class="flex-1 px-6 py-4 rounded-xl border-none focus:outline-none focus:ring-4 focus:ring-white/30 text-gray-900 placeholder-gray-500"
                    required
                >
                <button type="submit" class="px-8 py-4 bg-white text-primary-600 font-bold rounded-xl hover:bg-gray-100 transition-all duration-300 hover:shadow-xl whitespace-nowrap">
                    Subscribe Now
                </button>
            </div>
            <p class="text-white/70 text-sm mt-4">
                Join 10,000+ subscribers. Unsubscribe anytime.
            </p>
        </form>
    </div>
</section>

<!-- Trending Tags Section -->
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Trending Topics</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">Explore popular tags and discover new content</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3" data-aos="fade-up" data-aos-delay="200">
            @forelse($tags->take(20) as $tag)
                <a href="{{ route('blogs.tag', $tag->slug) }}" 
                   class="group inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 hover:bg-gradient-to-r hover:from-primary-500 hover:to-purple-500 text-gray-700 dark:text-gray-300 hover:text-white rounded-full shadow-sm hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <span class="font-medium">#{{ $tag->name }}</span>
                    <span class="ml-2 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 group-hover:bg-white/20 text-xs rounded-full transition-colors">
                        {{ $tag->blogs_count ?? 0 }}
                    </span>
                </a>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No tags available yet</p>
            @endforelse
        </div>
    </div>
</section>
@endsection