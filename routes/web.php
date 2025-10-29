<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Developers;
use App\Livewire\Articles;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Developers Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/developers', Developers\Index::class)->name('developers.index');
    Route::get('/developers/create', Developers\Create::class)->name('developers.create');
    Route::get('/developers/{developer}/edit', Developers\Edit::class)->name('developers.edit');
});

// Articles Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/articles', Articles\Index::class)->name('articles.index');
    Route::get('/articles/create', Articles\Create::class)->name('articles.create');
    Route::get('/articles/{article}/edit', Articles\Edit::class)->name('articles.edit');
});

require __DIR__.'/auth.php';
