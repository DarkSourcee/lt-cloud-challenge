<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold">Articles</h2>
                    <a href="{{ route('articles.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Article
                    </a>
                </div>

                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Search -->
                <div class="mb-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search articles..." 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($articles as $article)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition">
                            @if($article->cover_image)
                                <img src="{{ Storage::url($article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <h3 class="text-lg font-bold mb-2 line-clamp-2">{{ $article->title }}</h3>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                    {!! Str::limit(strip_tags($article->content), 120) !!}
                                </div>

                                <!-- Developers Badge -->
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-sm rounded-full">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        {{ $article->developers->count() }} {{ Str::plural('Developer', $article->developers->count()) }}
                                    </span>
                                </div>

                                @if($article->published_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                        Published: {{ $article->published_at->format('M d, Y') }}
                                    </p>
                                @else
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mb-4">Draft</p>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <a href="{{ route('articles.edit', $article) }}" wire:navigate 
                                        class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                        Edit
                                    </a>
                                    <button wire:click="confirmDelete({{ $article->id }})" 
                                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No articles found. Create your first one!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" wire:click="cancelDelete">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this article? This action cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        Cancel
                    </button>
                    <button wire:click="deleteArticle" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
