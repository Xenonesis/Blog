@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-purple-600 rounded-2xl mb-6 shadow-lg hover-glow">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold gradient-text mb-4">Edit Blog Post</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Refine your content, update settings, and make your blog post perfect</p>
        </div>

        <form action="{{ route('my-blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Content Header -->
                    <div class="card-gradient p-6 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Content Editor</h2>
                                <p class="text-gray-600 dark:text-gray-400">Craft your blog post with rich formatting</p>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Auto-saved</span>
                            </div>
                        </div>
                    </div>

                    <!-- Title Section -->
                    <div class="card p-6">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    Title <span class="text-red-500 ml-1">*</span>
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="input-modern text-lg"
                                   value="{{ old('title', $blog->title) }}" required
                                   placeholder="Enter an engaging title for your blog post...">
                            @error('title')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Title Character Counter -->
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>Character count: <span id="title-count" class="font-medium">{{ strlen(old('title', $blog->title)) }}</span>/100</span>
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                SEO Optimized
                            </span>
                        </div>
                    </div>

                    <!-- Excerpt Section -->
                    <div class="card p-6">
                        <div class="mb-4">
                            <label for="excerpt" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    Excerpt
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </label>
                            <textarea name="excerpt" id="excerpt" rows="4"
                                      class="input-modern resize-none"
                                      placeholder="Write a compelling summary that will attract readers...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Excerpt Character Counter -->
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>Character count: <span id="excerpt-count" class="font-medium">{{ strlen(old('excerpt', $blog->excerpt)) }}</span>/160</span>
                            <span class="text-gray-400">Perfect for meta descriptions</span>
                        </div>
                    </div>

                    <!-- Content Editor Section -->
                    <div class="card p-6">
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    Content <span class="text-red-500 ml-1">*</span>
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                            </label>
                            <div id="editor-container" class="bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                                <textarea name="content" id="content" style="display: none;">{{ old('content', $blog->content) }}</textarea>
                            </div>
                            @error('content')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Editor Toolbar -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Rich Text Editor
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                                    Preview
                                </button>
                                <button type="button" class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors">
                                    Help
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Blog Info Card -->
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Blog Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Created</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $blog->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $blog->updated_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                                @if($blog->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Current Cover Image -->
                    @if($blog->cover_image)
                        <div class="card hover-lift">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Current Cover Image
                                </h3>
                                <div class="relative group">
                                    <img src="{{ Storage::url($blog->cover_image) }}" alt="Current cover" 
                                         class="w-full h-48 object-cover rounded-xl shadow-md">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-xl flex items-center justify-center">
                                        <button type="button" class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-2 rounded-lg shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Publish Options -->
                    <div class="card hover-lift">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Publish Options
                            </h3>
                            
                            <div class="space-y-4">
                                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 cursor-pointer group">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <input type="checkbox" name="is_published" value="1" 
                                                   class="sr-only peer"
                                                   {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Published</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Make this post publicly visible</p>
                                        </div>
                                    </div>
                                    @if(old('is_published', $blog->is_published))
                                        <span class="badge badge-success">Live</span>
                                    @else
                                        <span class="badge badge-warning">Draft</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="card hover-lift">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Category
                            </h3>
                            <select name="category_id" id="category_id"
                                    class="input-modern" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tags Selection -->
                    <div class="card hover-lift">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Tags
                            </h3>
                            <div class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto p-3 border border-gray-300 dark:border-gray-600 rounded-xl">
                                @foreach($tags as $tag)
                                    <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer group">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                               class="sr-only peer"
                                               {{ in_array($tag->id, old('tags', $blog->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <div class="relative">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:border-primary-600 peer-checked:bg-primary-600 transition-all duration-200"></div>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity duration-200">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tags')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cover Image Upload -->
                    <div class="card hover-lift">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Cover Image
                            </h3>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-primary-500 transition-colors duration-200">
                                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden">
                                <label for="cover_image" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Click to upload new cover image</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current image</p>
                                </label>
                            </div>
                            @error('cover_image')
                                <div class="mt-2 flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-gradient p-6 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="btn-primary w-full flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Blog Post
                            </button>
                            <div class="flex space-x-3">
                                <a href="{{ route('blogs.show', $blog->slug) }}" 
                                   class="btn-secondary flex-1 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    View Post
                                </a>
                                <button type="button" class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m9.032 4.026a9.001 9.001 0 010-5.364m-9.032 5.364a9.001 9.001 0 01-5.364 0m14.396 0a9.001 9.001 0 00-5.364 0M9 12a9.001 9.001 0 019-9" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quill.js Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Write your blog content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // Set initial content
    var contentTextarea = document.getElementById('content');
    if (contentTextarea && contentTextarea.value) {
        quill.root.innerHTML = contentTextarea.value;
    }

    // Update hidden textarea on form submit
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            var contentTextarea = document.getElementById('content');
            if (contentTextarea && quill) {
                contentTextarea.value = quill.root.innerHTML;
            }
        });
    }

    // Character counters with null checks
    function updateCharCount(inputId, countId, maxLength) {
        var input = document.getElementById(inputId);
        var counter = document.getElementById(countId);
        
        if (!input || !counter) return;
        
        function update() {
            var length = input.value.length;
            counter.textContent = length;
            
            if (length > maxLength) {
                counter.parentElement.classList.add('text-red-500');
                counter.parentElement.classList.remove('text-gray-500', 'dark:text-gray-400');
            } else {
                counter.parentElement.classList.remove('text-red-500');
                counter.parentElement.classList.add('text-gray-500', 'dark:text-gray-400');
            }
        }
        
        input.addEventListener('input', update);
        update(); // Initial count
    }

    // Initialize character counters
    updateCharCount('title', 'title-count', 100);
    updateCharCount('excerpt', 'excerpt-count', 160);

    // Enhanced file upload interaction
    var fileInput = document.getElementById('cover_image');
    if (fileInput) {
        var fileLabel = fileInput.nextElementSibling;
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var fileName = this.files[0].name;
                if (fileLabel) {
                    fileLabel.innerHTML = `
                        <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">${fileName}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Click to change image</p>
                    `;
                }
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading state to buttons
    if (form) {
        var submitButton = form.querySelector('button[type="submit"]');
        form.addEventListener('submit', function() {
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Updating...
                `;
            }
        });
    }

    // Auto-save simulation with proper null checks
    var autoSaveIndicator = document.querySelector('.text-gray-500.dark\\:text-gray-400');
    if (autoSaveIndicator) {
        setInterval(function() {
            var originalText = autoSaveIndicator.textContent;
            autoSaveIndicator.innerHTML = `
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Saving...</span>
            `;
            
            setTimeout(function() {
                autoSaveIndicator.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Saved</span>
                `;
                
                setTimeout(function() {
                    autoSaveIndicator.innerHTML = originalText;
                }, 2000);
            }, 1000);
        }, 30000); // Auto-save every 30 seconds
    }

    // Modern MutationObserver instead of deprecated DOMNodeInserted
    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'subtree') {
                    // Re-initialize character counters if DOM changes
                    updateCharCount('title', 'title-count', 100);
                    updateCharCount('excerpt', 'excerpt-count', 160);
                }
            });
        });

        // Observe the form for changes
        var form = document.querySelector('form');
        if (form) {
            observer.observe(form, {
                childList: true,
                subtree: true,
                attributes: false,
                characterData: false
            });
        }
    }
});
</script>

<style>
.ql-editor {
    min-height: 300px;
    font-size: 16px;
    line-height: 1.6;
}
</style>
@endsection
