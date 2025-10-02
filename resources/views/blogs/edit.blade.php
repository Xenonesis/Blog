@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Edit Blog Post</h1>

                <form action="{{ route('my-blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                       value="{{ old('title', $blog->title) }}" required>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Excerpt -->
                            <div>
                                <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Excerpt
                                </label>
                                <textarea name="excerpt" id="excerpt" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                          placeholder="Short description of your blog post...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                @error('excerpt')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rich Text Content -->
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Content <span class="text-red-500">*</span>
                                </label>
                                <div id="editor-container" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                    <textarea name="content" id="content" style="display: none;">{{ old('content', $blog->content) }}</textarea>
                                </div>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Current Cover Image -->
                            @if($blog->cover_image)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Current Cover Image
                                    </label>
                                    <img src="{{ Storage::url($blog->cover_image) }}" alt="Current cover" 
                                         class="w-full h-32 object-cover rounded-lg">
                                </div>
                            @endif

                            <!-- Publish Options -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Publish Options</h3>
                                
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_published" value="1" 
                                               class="mr-2 text-blue-600" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Published</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="category_id"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tags
                                </label>
                                <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3 space-y-2">
                                    @foreach($tags as $tag)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                   class="mr-2 text-blue-600"
                                                   {{ in_array($tag->id, old('tags', $blog->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('tags')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cover Image -->
                            <div>
                                <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    New Cover Image
                                </label>
                                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('cover_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col space-y-2">
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update Blog
                                </button>
                                <a href="{{ route('blogs.show', $blog->slug) }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
    var initialContent = document.getElementById('content').value;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    // Update hidden textarea on form submit
    var form = document.querySelector('form');
    form.addEventListener('submit', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });
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