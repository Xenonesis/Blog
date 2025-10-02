<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#3b82f6">

        <!-- Prevent flash of incorrect theme on load: set theme early and suppress transitions -->
        <style>
            /* When present, remove all transitions while initial theme is applied */
            .no-transitions * {
                transition: none !important;
                animation: none !important;
            }
        </style>
        <script>
            (function() {
                try {
                    var root = document.documentElement;
                    // determine preference: explicit localStorage > prefers-color-scheme
                    var stored = localStorage.getItem('theme');
                    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    var useDark = stored ? (stored === 'dark') : prefersDark;

                    // add the class early so Tailwind's dark: rules apply immediately
                    if (useDark) root.classList.add('dark'); else root.classList.remove('dark');

                    // temporarily suppress transitions to avoid flash
                    root.classList.add('no-transitions');
                    // remove the suppression on the next paint
                    requestAnimationFrame(function() {
                        setTimeout(function() {
                            root.classList.remove('no-transitions');
                        }, 0);
                    });
                } catch (e) {
                    // silent fallback
                }
            })();
        </script>

        <title>@yield('title', config('app.name', 'Modern Blog'))</title>
        <meta name="description" content="@yield('description', 'A modern, feature-rich blog platform built with Laravel')">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', config('app.name', 'Modern Blog'))">
        <meta property="og:description" content="@yield('description', 'A modern, feature-rich blog platform built with Laravel')">
        <meta property="og:image" content="@yield('og-image', asset('images/og-default.jpg'))">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('title', config('app.name', 'Modern Blog'))">
        <meta property="twitter:description" content="@yield('description', 'A modern, feature-rich blog platform built with Laravel')">
        <meta property="twitter:image" content="@yield('og-image', asset('images/og-default.jpg'))">

        <!-- Favicons -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 scrollbar-thin">
        <!-- Reading Progress Bar -->
        <div id="reading-progress" class="fixed top-0 left-0 z-50 h-1 bg-gradient-to-r from-primary-500 to-purple-500 transition-all duration-150" style="width: 0%"></div>
        
        <!-- Skip to Content -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 z-50 btn-primary">
            Skip to content
        </a>
        
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {!! $header !!}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/50 border-l-4 border-green-400 p-4 animate-slide-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/50 border-l-4 border-red-400 p-4 animate-slide-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main id="main-content" class="flex-1">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-2xl font-bold gradient-text mb-4">{{ config('app.name', 'Modern Blog') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                A modern, feature-rich blog platform where ideas come to life. Share your thoughts, discover amazing content, and connect with a vibrant community.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary-500 transition-colors">
                                    <span class="sr-only">GitHub</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('blogs.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">All Blogs</a></li>
                                <li><a href="{{ route('blogs.categories') }}" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Categories</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Authors</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">About</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Support</h4>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Help Center</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Contact</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Privacy Policy</a></li>
                                <li><a href="#" class="text-gray-600 dark:text-gray-400 hover:text-primary-500 transition-colors">Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-center text-gray-500 dark:text-gray-400">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Modern Blog') }}. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Scroll to Top Button -->
        <button
            id="scroll-to-top"
            class="fixed bottom-8 right-8 z-40 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 pointer-events-none hover:scale-110"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </button>

        <!-- Scripts -->
        @stack('scripts')
        
        <script>
            // Show/hide scroll to top button
            window.addEventListener('scroll', function() {
                const scrollToTop = document.getElementById('scroll-to-top');
                if (window.pageYOffset > 300) {
                    scrollToTop.classList.remove('opacity-0', 'pointer-events-none');
                } else {
                    scrollToTop.classList.add('opacity-0', 'pointer-events-none');
                }
            });

            // Navigation search functionality
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchResults = document.getElementById('search-results');
                let searchTimeout;

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.trim();
                        clearTimeout(searchTimeout);

                        if (query.length < 2) {
                            searchResults.classList.add('hidden');
                            return;
                        }

                        searchTimeout = setTimeout(() => {
                            // Redirect to search page for now (can be enhanced with AJAX later)
                            window.location.href = '{{ route("blogs.search") }}?q=' + encodeURIComponent(query);
                        }, 500);
                    });

                    // Handle Enter key
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            const query = this.value.trim();
                            if (query) {
                                window.location.href = '{{ route("blogs.search") }}?q=' + encodeURIComponent(query);
                            }
                        }
                    });
                }
            });
        </script>
    </body>
</html>
