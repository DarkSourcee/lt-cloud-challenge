<?php

namespace App\Livewire\Developers;

use App\Models\Developer;
use Livewire\Component;

class Edit extends Component
{
    public Developer $developer;
    public $name;
    public $email;
    public $seniority;
    public $skillInput = '';
    public $skills = [];

    public function mount(Developer $developer)
    {
        $this->authorize('update', $developer);

        $this->developer = $developer;
        $this->name = $developer->name;
        $this->email = $developer->email;
        $this->seniority = $developer->seniority;
        $this->skills = $developer->skills ?? [];
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:developers,email,' . $this->developer->id,
            'seniority' => 'required|in:Jr,Pl,Sr',
            'skills' => 'required|array|min:1',
        ];
    }

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

    public function update()
    {
        $this->authorize('update', $this->developer);

        $this->validate();

        $this->developer->update([
            'name' => $this->name,
            'email' => $this->email,
            'seniority' => $this->seniority,
            'skills' => $this->skills,
        ]);

        session()->flash('message', 'Developer updated successfully.');

        return redirect()->route('developers.index');
    }

    public function render()
    {
        return view('livewire.developers.edit')->layout('layouts.app');
    }
}
