@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tags</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage blog tags</p>
            </div>
            <a href="{{ route('admin.tags.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Tag
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex flex-wrap gap-3">
            @forelse($tags ?? [] as $tag)
                <div class="inline-flex items-center bg-blue-50 dark:bg-blue-900 px-4 py-2 rounded-full">
                    <span class="text-blue-800 dark:text-blue-200 font-medium">{{ $tag->name }}</span>
                    <span class="ml-2 text-blue-600 dark:text-blue-300 text-sm">({{ $tag->blogs_count ?? 0 }})</span>
                    <div class="ml-3 flex space-x-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete this tag?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 w-full">
                    <p class="text-gray-500 dark:text-gray-400">No tags found. Create your first tag.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
