<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Create Article</h2>
                    <a href="{{ route('articles.index') }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        ← Back to List
                    </a>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium mb-2">Title *</label>
                        <input type="text" id="title" wire:model="title" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('title') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium mb-2">Content (HTML) *</label>
                        <textarea id="content" wire:model="content" rows="10"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        @error('content') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">You can use HTML tags like &lt;p&gt;, &lt;h1&gt;, &lt;strong&gt;, etc.</p>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium mb-2">Cover Image</label>
                        <input type="file" id="cover_image" wire:model="cover_image" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('cover_image') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @if ($cover_image)
                            <div class="mt-2">
                                <img src="{{ $cover_image->temporaryUrl() }}" class="h-32 rounded-lg" alt="Preview">
                            </div>
                        @endif
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium mb-2">Published Date</label>
                        <input type="date" id="published_at" wire:model="published_at" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('published_at') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to save as draft</p>
                    </div>

                    <!-- Select Developers -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Associated Developers *</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            @forelse($developers as $developer)
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded cursor-pointer">
                                    <input type="checkbox" wire:model="selectedDevelopers" value="{{ $developer->id }}"
                                        class="rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <span class="font-medium">{{ $developer->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ $developer->seniority }})</span>
                                    </div>
                                </label>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No developers available. <a href="{{ route('developers.create') }}" class="text-blue-600 hover:underline">Create one first</a>.</p>
                            @endforelse
                        </div>
                        @error('selectedDevelopers') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('articles.index') }}" wire:navigate 
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Create Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
