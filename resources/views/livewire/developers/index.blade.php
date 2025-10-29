<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold">Developers</h2>
                    <a href="{{ route('developers.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Developer
                    </a>
                </div>

                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Seniority</label>
                        <select wire:model.live="seniorityFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Levels</option>
                            <option value="Jr">Junior</option>
                            <option value="Pl">Pleno</option>
                            <option value="Sr">Senior</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Skill</label>
                        <select wire:model.live="skillFilter" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">All Skills</option>
                            @foreach($allSkills as $skill)
                                <option value="{{ $skill }}">{{ $skill }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Grid/List View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($developers as $developer)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold mb-1">{{ $developer->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $developer->email }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $developer->seniority === 'Sr' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                    {{ $developer->seniority === 'Pl' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                    {{ $developer->seniority === 'Jr' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                    {{ $developer->seniority }}
                                </span>
                            </div>
                            
                            <!-- Skills -->
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Skills:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($developer->skills ?? [] as $skill)
                                        <span class="px-2 py-1 bg-gray-200 dark:bg-gray-600 text-xs rounded">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Articles Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm rounded-full">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $developer->articles->count() }} {{ Str::plural('Article', $developer->articles->count()) }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('developers.edit', $developer) }}" wire:navigate 
                                    class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                                    Edit
                                </a>
                                <button wire:click="confirmDelete({{ $developer->id }})" 
                                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No developers found. Create your first one!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $developers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" wire:click="cancelDelete">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this developer? This action cannot be undone.</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        Cancel
                    </button>
                    <button wire:click="deleteDeveloper" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
