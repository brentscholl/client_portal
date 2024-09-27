<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Models\Team;
use App\Traits\Slideable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use Slideable;

    public $title;
    public $description;

    public function load() {

    }

    public function rules() {
        return [
            'title' => 'required|unique:teams,title',
            'description' => 'max:500'
        ];
    }

    public function addTeam() {
        $this->validate();
        DB::beginTransaction();

        // Create the client
        $team = Team::create([
            'title'        => $this->title,
            'description' => $this->description,
        ]);

        DB::commit();

        $this->closeSlideout();
        $this->emit('teamCreated');
        flash('Team Created!')->success()->livewire($this);
        $this->reset();
        return redirect()->route('admin.teams.index');
    }

    public function render()
    {
        return view('livewire.admin.teams.create');
    }
}
