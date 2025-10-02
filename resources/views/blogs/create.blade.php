<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Create New Blog Post</h1>

                    <form action="{{ route('my-blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-6">
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Excerpt
                            </label>
                            <textarea name="excerpt" id="excerpt" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Short description of your blog post...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tags
                            </label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($tags as $tag)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                               class="mr-2 text-blue-600"
                                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('tags')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Image -->
                        <div class="mb-6">
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cover Image
                            </label>
                            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('cover_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" id="content" rows="15"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Write your blog content here..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Publish Status -->
                        <div class="mb-8">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_published" value="1" 
                                       class="mr-2 text-blue-600" {{ old('is_published', true) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Publish immediately
                                </span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between">
                            <a href="{{ route('blogs.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Blog Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>