<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Developer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';
    public $content = '';
    public $cover_image;
    public $published_at = '';
    public $selectedDevelopers = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'cover_image' => 'nullable|image|max:2048',
        'published_at' => 'nullable|date',
        'selectedDevelopers' => 'required|array|min:1',
    ];

    public function save()
    {
        $this->authorize('create', Article::class);

        $this->validate();

        $coverImagePath = null;
        if ($this->cover_image) {
            $coverImagePath = $this->cover_image->store('articles', 'public');
        }

        $article = Article::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'content' => $this->content,
            'cover_image' => $coverImagePath,
            'published_at' => $this->published_at ?: null,
        ]);

        $article->developers()->attach($this->selectedDevelopers);

        session()->flash('message', 'Article created successfully.');

        return redirect()->route('articles.index');
    }

    public function render()
    {
        $developers = Developer::where('user_id', auth()->id())->get();

        return view('livewire.articles.create', [
            'developers' => $developers,
        ])->layout('layouts.app');
    }
}
