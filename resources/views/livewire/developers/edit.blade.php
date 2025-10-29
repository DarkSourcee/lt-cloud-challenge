<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Edit Developer</h2>
                    <a href="{{ route('developers.index') }}" wire:navigate class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        ← Back to List
                    </a>
                </div>

                <form wire:submit="update" class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Name *</label>
                        <input type="text" id="name" wire:model="name" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('name') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                        <input type="email" id="email" wire:model="email" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @error('email') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Seniority -->
                    <div>
                        <label for="seniority" class="block text-sm font-medium mb-2">Seniority Level *</label>
                        <select id="seniority" wire:model="seniority" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Jr">Junior</option>
                            <option value="Pl">Pleno</option>
                            <option value="Sr">Senior</option>
                        </select>
                        @error('seniority') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Skills -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Skills *</label>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="skillInput" wire:keydown.enter.prevent="addSkill"
                                placeholder="Type a skill and press Enter" 
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="button" wire:click="addSkill" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                Add
                            </button>
                        </div>
                        
                        <!-- Skills Display -->
                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach($skills as $index => $skill)
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                                    {{ $skill }}
                                    <button type="button" wire:click="removeSkill({{ $index }})" class="ml-2 text-blue-600 dark:text-blue-300 hover:text-blue-800">
                                        ×
                                    </button>
                                </span>
                            @endforeach
                        </div>
                        
                        @error('skills') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('developers.index') }}" wire:navigate 
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            Update Developer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
