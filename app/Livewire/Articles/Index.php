<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $articleToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($articleId)
    {
        $this->articleToDelete = $articleId;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->articleToDelete = null;
    }

    public function deleteArticle()
    {
        $article = Article::findOrFail($this->articleToDelete);
        
        $this->authorize('delete', $article);
        
        $article->delete();
        
        $this->confirmingDeletion = false;
        $this->articleToDelete = null;
        
        session()->flash('message', 'Article deleted successfully.');
    }

    public function render()
    {
        $query = Article::where('user_id', auth()->id())
            ->with('developers');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        $articles = $query->latest()->paginate(12);

        return view('livewire.articles.index', [
            'articles' => $articles,
        ])->layout('layouts.app');
    }
}
