<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Manage your developers and articles from here.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Developers Card -->
                <a href="{{ route('developers.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-700 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="text-4xl font-bold">{{ auth()->user()->developers()->count() }}</span>
                        </div>
                        <h3 class="text-xl font-semibold">Developers</h3>
                        <p class="text-blue-100 mt-2">Manage your developer profiles</p>
                    </div>
                </a>

                <!-- Articles Card -->
                <a href="{{ route('articles.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-700 overflow-hidden shadow-lg sm:rounded-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-4xl font-bold">{{ auth()->user()->articles()->count() }}</span>
                        </div>
                        <h3 class="text-xl font-semibold">Articles</h3>
                        <p class="text-purple-100 mt-2">Manage your article content</p>
                    </div>
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('developers.create') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">New Developer</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Add a developer profile</p>
                            </div>
                        </a>
                        <a href="{{ route('articles.create') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">New Article</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Create a new article</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
