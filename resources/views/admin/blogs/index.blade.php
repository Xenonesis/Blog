@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Advanced Blog Management</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage, filter, and analyze all blog posts</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.analytics.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📊 Analytics
                </a>
                <a href="{{ route('admin.blogs.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ✍️ Create Blog
                </a>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Title or content..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select name="category" id="category" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Author Filter -->
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Author</label>
                <select name="author" id="author" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">All Authors</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
            </div>

            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-4 lg:col-span-6 flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    🔍 Apply Filters
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-6">
        <form id="bulk-form" action="{{ route('admin.blogs.bulk-action') }}" method="POST" class="flex items-center space-x-4">
            @csrf
            <div class="flex items-center">
                <input type="checkbox" id="select-all" class="mr-2">
                <label for="select-all" class="text-sm text-gray-700 dark:text-gray-300">Select All</label>
            </div>
            
            <select name="action" id="bulk-action" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                <option value="">Choose Action</option>
                <option value="publish">📢 Publish</option>
                <option value="unpublish">📝 Unpublish</option>
                <option value="hide">👁️ Hide</option>
                <option value="show">👁️‍🗨️ Show</option>
                <option value="delete">🗑️ Delete</option>
            </select>
            
            <button type="submit" id="bulk-submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded text-sm" disabled>
                Apply to Selected
            </button>
            
            <span id="selected-count" class="text-sm text-gray-500 dark:text-gray-400">0 selected</span>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <input type="checkbox" id="header-checkbox" class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Blog</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stats</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SEO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="blog_ids[]" value="{{ $blog->id }}" class="blog-checkbox rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($blog->cover_image)
                                        <img src="{{ Storage::url($blog->cover_image) }}" alt="{{ $blog->title }}" 
                                             class="w-12 h-12 object-cover rounded-lg mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                            <span class="text-white font-bold">{{ substr($blog->title, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $blog->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ Str::limit($blog->excerpt, 60) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $blog->user?->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 px-2 py-1 rounded-full text-xs">
                                    {{ $blog->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($blog->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                            Draft
                                        </span>
                                    @endif
                                    @if($blog->is_hidden)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            Hidden
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex space-x-2">
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $blog->views_count }} views
                                    </span>
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $blog->comments->count() }} comments
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $blog->created_at->format('M d, Y') }}
                                @if($blog->scheduled_at)
                                    <br><span class="text-xs text-blue-600">⏰ {{ $blog->scheduled_at->format('M d, H:i') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($blog->seo_score)
                                    <div class="flex items-center">
                                        <div class="w-12 h-2 bg-gray-200 rounded-full mr-2">
                                            <div class="h-2 rounded-full {{ $blog->seo_score['score'] >= 80 ? 'bg-green-500' : ($blog->seo_score['score'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                 style="width: {{ $blog->seo_score['score'] }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ $blog->seo_score['score'] }}%</span>
                                    </div>
                                    @if($blog->reading_time)
                                        <div class="text-xs text-gray-500 mt-1">📖 {{ $blog->reading_time }}min read</div>
                                    @endif
                                @else
                                    <button onclick="analyzeSEO({{ $blog->id }})" class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded">
                                        🔍 Analyze
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex flex-wrap gap-1 text-xs">
                                    <a href="{{ route('blogs.show', $blog->slug) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded">
                                        👁️ View
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" 
                                       class="bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-2 py-1 rounded">
                                        ✏️ Edit
                                    </a>
                                    <a href="{{ route('admin.analytics.blog', $blog) }}" 
                                       class="bg-green-100 hover:bg-green-200 text-green-800 px-2 py-1 rounded">
                                        📊 Analytics
                                    </a>
                                    @if($blog->status !== 'scheduled')
                                        <button onclick="showScheduleModal({{ $blog->id }})" 
                                                class="bg-purple-100 hover:bg-purple-200 text-purple-800 px-2 py-1 rounded">
                                            ⏰ Schedule
                                        </button>
                                    @endif
                                    <form action="{{ route('admin.blogs.toggle-visibility', $blog) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-2 py-1 rounded">
                                            {{ $blog->is_hidden ? '👁️‍🗨️ Show' : '👁️ Hide' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 hover:bg-red-200 text-red-800 px-2 py-1 rounded"
                                                onclick="return confirm('Are you sure you want to delete this blog?')">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                No blogs found. Try adjusting your filters or <a href="{{ route('admin.blogs.create') }}" class="text-blue-600 hover:text-blue-800">create your first blog</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($blogs->hasPages())
        <div class="mt-6">
            {{ $blogs->links() }}
        </div>
    @endif
</div>

<!-- Schedule Modal -->
<div id="schedule-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Schedule Blog Post</h3>
        <form id="schedule-form" method="POST">
            @csrf
            <div class="mb-4">
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Schedule Date & Time
                </label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at" required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="auto_publish" value="1" checked class="mr-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Auto-publish at scheduled time</span>
                </label>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeScheduleModal()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Schedule
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Bulk Operations
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const blogCheckboxes = document.querySelectorAll('.blog-checkbox');
    const bulkForm = document.getElementById('bulk-form');
    const bulkSubmit = document.getElementById('bulk-submit');
    const selectedCount = document.getElementById('selected-count');
    const bulkAction = document.getElementById('bulk-action');

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.blog-checkbox:checked');
        selectedCount.textContent = `${checkedBoxes.length} selected`;
        bulkSubmit.disabled = checkedBoxes.length === 0 || !bulkAction.value;
    }

    selectAllCheckbox.addEventListener('change', function() {
        blogCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    blogCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    bulkAction.addEventListener('change', updateSelectedCount);

    bulkForm.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.blog-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one blog post.');
            return;
        }

        const action = bulkAction.value;
        if (action === 'delete') {
            if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} blog post(s)? This action cannot be undone.`)) {
                e.preventDefault();
                return;
            }
        } else {
            if (!confirm(`Are you sure you want to ${action} ${checkedBoxes.length} blog post(s)?`)) {
                e.preventDefault();
                return;
            }
        }
    });
});

// SEO Analysis
function analyzeSEO(blogId) {
    fetch(`/admin/blogs/${blogId}/seo-analysis`)
        .then(response => response.json())
        .then(data => {
            alert(`SEO Score: ${data.seo_score.score}%\nReading Time: ${data.reading_time} minutes\n\nRecommendations:\n${data.seo_score.recommendations.join('\n')}`);
            location.reload();
        })
        .catch(error => {
            alert('Error analyzing SEO. Please try again.');
        });
}

// Schedule Modal
function showScheduleModal(blogId) {
    const modal = document.getElementById('schedule-modal');
    const form = document.getElementById('schedule-form');
    form.action = `/admin/blogs/${blogId}/schedule`;
    
    // Set minimum date to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('scheduled_at').min = now.toISOString().slice(0, 16);
    
    modal.classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('schedule-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('schedule-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScheduleModal();
    }
});
</script>
@endsection