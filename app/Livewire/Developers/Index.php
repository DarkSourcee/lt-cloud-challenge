<?php

namespace App\Livewire\Developers;

use App\Models\Developer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $skillFilter = '';
    public $seniorityFilter = '';
    public $confirmingDeletion = false;
    public $developerToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'skillFilter' => ['except' => ''],
        'seniorityFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSkillFilter()
    {
        $this->resetPage();
    }

    public function updatingSeniorityFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($developerId)
    {
        $this->developerToDelete = $developerId;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->developerToDelete = null;
    }

    public function deleteDeveloper()
    {
        $developer = Developer::findOrFail($this->developerToDelete);
        
        $this->authorize('delete', $developer);
        
        $developer->delete();
        
        $this->confirmingDeletion = false;
        $this->developerToDelete = null;
        
        session()->flash('message', 'Developer deleted successfully.');
    }

    public function render()
    {
        $query = Developer::where('user_id', auth()->id())
            ->with('articles');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->skillFilter) {
            $query->whereJsonContains('skills', $this->skillFilter);
        }

        if ($this->seniorityFilter) {
            $query->where('seniority', $this->seniorityFilter);
        }

        $developers = $query->latest()->paginate(12);

        // Get all unique skills for filter dropdown
        $allSkills = Developer::where('user_id', auth()->id())
            ->get()
            ->pluck('skills')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('livewire.developers.index', [
            'developers' => $developers,
            'allSkills' => $allSkills,
        ])->layout('layouts.app');
    }
}
