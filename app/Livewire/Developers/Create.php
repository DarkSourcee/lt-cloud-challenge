<?php

namespace App\Livewire\Developers;

use App\Models\Developer;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $seniority = 'Jr';
    public $skillInput = '';
    public $skills = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:developers,email',
        'seniority' => 'required|in:Jr,Pl,Sr',
        'skills' => 'required|array|min:1',
    ];

    public function addSkill()
    {
        $skill = trim($this->skillInput);
        
        if ($skill && !in_array($skill, $this->skills)) {
            $this->skills[] = $skill;
            $this->skillInput = '';
        }
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function save()
    {
        $this->authorize('create', Developer::class);

        $this->validate();

        Developer::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'email' => $this->email,
            'seniority' => $this->seniority,
            'skills' => $this->skills,
        ]);

        session()->flash('message', 'Developer created successfully.');

        return redirect()->route('developers.index');
    }

    public function render()
    {
        return view('livewire.developers.create')->layout('layouts.app');
    }
}
