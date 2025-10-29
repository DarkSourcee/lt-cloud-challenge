<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Developer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Article $article;
    public $title;
    public $content;
    public $cover_image;
    public $existing_cover_image;
    public $published_at;
    public $selectedDevelopers = [];

    public function mount(Article $article)
    {
        $this->authorize('update', $article);

        $this->article = $article;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->existing_cover_image = $article->cover_image;
        $this->published_at = $article->published_at ? $article->published_at->format('Y-m-d') : '';
        $this->selectedDevelopers = $article->developers->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
            'selectedDevelopers' => 'required|array|min:1',
        ];
    }

    public function update()
    {
        $this->authorize('update', $this->article);

        $this->validate();

        $coverImagePath = $this->existing_cover_image;

        if ($this->cover_image) {
            // Delete old image if exists
            if ($this->existing_cover_image && Storage::disk('public')->exists($this->existing_cover_image)) {
                Storage::disk('public')->delete($this->existing_cover_image);
            }
            
            $coverImagePath = $this->cover_image->store('articles', 'public');
        }

        $this->article->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . $this->article->id,
            'content' => $this->content,
            'cover_image' => $coverImagePath,
            'published_at' => $this->published_at ?: null,
        ]);

        $this->article->developers()->sync($this->selectedDevelopers);

        session()->flash('message', 'Article updated successfully.');

        return redirect()->route('articles.index');
    }

    public function render()
    {
        $developers = Developer::where('user_id', auth()->id())->get();

        return view('livewire.articles.edit', [
            'developers' => $developers,
        ])->layout('layouts.app');
    }
}
