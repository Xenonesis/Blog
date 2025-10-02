import './bootstrap';
import Alpine from 'alpinejs';
import 'aos/dist/aos.css';
import AOS from 'aos';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css';

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Initialize Alpine.js
window.Alpine = Alpine;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

// Modern Blog App functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    // Initialize syntax highlighting
    hljs.highlightAll();
    
    // Initialize modern features
    initializeModernFeatures();
    
    // Add a small delay to ensure all elements are rendered before animations
    setTimeout(() => {
        initializeAnimations();
    }, 100);
    
    initializeInteractions();
});

// Modern Features
function initializeModernFeatures() {
    // Dark mode toggle
    initializeDarkMode();
    
    // Progressive image loading
    initializeLazyLoading();
    
    // Smooth scroll for anchor links
    initializeSmoothScroll();
    
    // Reading progress indicator
    initializeReadingProgress();
    
    // Search functionality enhancements
    initializeSearchEnhancements();
    
    // Infinite scroll for blog listings
    initializeInfiniteScroll();
}

// Dark Mode Toggle
function initializeDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const html = document.documentElement;
    
    // Check for saved dark mode preference or default to system preference
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
        html.classList.add('dark');
    }
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    }
}

// Lazy Loading for Images
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Smooth Scroll
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Reading Progress
function initializeReadingProgress() {
    const progressBar = document.getElementById('reading-progress');
    if (progressBar) {
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    }
}

// Enhanced Search
function initializeSearchEnhancements() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;
    
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                searchResults.classList.add('hidden');
            }
        });
    }
}

// Perform Search (AJAX)
async function performSearch(query) {
    try {
        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        displaySearchResults(data.blogs);
    } catch (error) {
        console.error('Search error:', error);
    }
}

// Display Search Results
function displaySearchResults(blogs) {
    const searchResults = document.getElementById('search-results');
    if (!searchResults) return;
    
    searchResults.innerHTML = '';
    
    if (blogs.length === 0) {
        searchResults.innerHTML = '<div class="p-4 text-gray-500">No results found</div>';
    } else {
        blogs.forEach(blog => {
            const item = createSearchResultItem(blog);
            searchResults.appendChild(item);
        });
    }
    
    searchResults.classList.remove('hidden');
}

// Create Search Result Item
function createSearchResultItem(blog) {
    const item = document.createElement('div');
    item.className = 'p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-600';
    item.innerHTML = `
        <h4 class="font-medium text-gray-900 dark:text-white">${blog.title}</h4>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${blog.excerpt || ''}</p>
        <div class="flex items-center mt-2 text-xs text-gray-500">
            <span>${blog.category?.name || 'Uncategorized'}</span>
            <span class="mx-2">•</span>
            <span>${blog.published_at}</span>
        </div>
    `;
    
    item.addEventListener('click', () => {
        window.location.href = `/blogs/${blog.slug}`;
    });
    
    return item;
}

// Infinite Scroll
function initializeInfiniteScroll() {
    let page = 1;
    let loading = false;
    const loadMoreTrigger = document.getElementById('load-more-trigger');
    
    if (loadMoreTrigger) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !loading) {
                loadMoreBlogs();
            }
        });
        
        observer.observe(loadMoreTrigger);
    }
}

// Load More Blogs
async function loadMoreBlogs() {
    loading = true;
    page++;
    
    try {
        const response = await fetch(`/api/blogs?page=${page}`);
        const data = await response.json();
        
        if (data.blogs.length > 0) {
            appendBlogs(data.blogs);
        } else {
            document.getElementById('load-more-trigger')?.remove();
        }
    } catch (error) {
        console.error('Load more error:', error);
    } finally {
        loading = false;
    }
}

// Animations
function initializeAnimations() {
    // Helper function to safely animate elements if they exist
    function animateIfExists(selector, animation) {
        const elements = document.querySelectorAll(selector);
        if (elements.length > 0) {
            return animation(elements);
        }
        return null;
    }
    
    // Hero section animation - only if elements exist
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroCta = document.querySelector('.hero-cta');
    
    if (heroTitle || heroSubtitle || heroCta) {
        const tl = gsap.timeline();
        
        if (heroTitle) {
            tl.from('.hero-title', { duration: 1, y: 50, opacity: 0, ease: 'power3.out' });
        }
        if (heroSubtitle) {
            tl.from('.hero-subtitle', { duration: 1, y: 30, opacity: 0, ease: 'power3.out' }, '-=0.5');
        }
        if (heroCta) {
            tl.from('.hero-cta', { duration: 1, y: 20, opacity: 0, ease: 'power3.out' }, '-=0.5');
        }
    }
    
    // Blog cards stagger animation - only if elements exist
    animateIfExists('.blog-card', (elements) => {
        const blogGrid = document.querySelector('.blog-grid');
        if (blogGrid) {
            return gsap.from('.blog-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                stagger: 0.1,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.blog-grid',
                    start: 'top 80%'
                }
            });
        }
    });
    
    // Parallax effect for hero backgrounds - only if elements exist
    animateIfExists('.parallax-bg', (elements) => {
        return gsap.to('.parallax-bg', {
            yPercent: -50,
            ease: 'none',
            scrollTrigger: {
                trigger: '.parallax-bg',
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    });
}

// Interactive Features
function initializeInteractions() {
    // Like/Unlike functionality
    window.toggleLike = async function(type, id, action) {
        try {
            const response = await fetch('/api/likes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    likeable_type: type,
                    likeable_id: id,
                    type: action
                })
            });
            
            const data = await response.json();
            updateLikeButtons(type, id, data);
        } catch (error) {
            console.error('Like error:', error);
        }
    };
    
    // Comment functionality
    window.showReplyForm = function(commentId) {
        const form = document.getElementById(`reply-form-${commentId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    };
    
    // Share functionality
    window.shareBlog = async function(title, url) {
        if (navigator.share) {
            try {
                await navigator.share({ title, url });
            } catch (error) {
                console.log('Share canceled or failed');
            }
        } else {
            // Fallback to clipboard
            await navigator.clipboard.writeText(url);
            showNotification('Link copied to clipboard!');
        }
    };
    
    // Bookmark functionality
    window.toggleBookmark = async function(blogId) {
        try {
            const response = await fetch('/api/bookmarks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ blog_id: blogId })
            });
            
            const data = await response.json();
            updateBookmarkButton(blogId, data.bookmarked);
        } catch (error) {
            console.error('Bookmark error:', error);
        }
    };
}

// Utility Functions
function updateLikeButtons(type, id, data) {
    const likesCount = document.getElementById(`${type}-${id}-likes-count`);
    const dislikesCount = document.getElementById(`${type}-${id}-dislikes-count`);
    
    if (likesCount) likesCount.textContent = data.likes_count;
    if (dislikesCount) dislikesCount.textContent = data.dislikes_count;
}

function updateBookmarkButton(blogId, isBookmarked) {
    const button = document.getElementById(`bookmark-${blogId}`);
    if (button) {
        button.classList.toggle('text-yellow-500', isBookmarked);
        button.classList.toggle('text-gray-400', !isBookmarked);
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('opacity-0', 'transform', 'translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize Alpine.js
Alpine.start();
