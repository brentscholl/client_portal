<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Models\User;
use App\Traits\HasMenu;
use Livewire\Component;

class TeamMemberCard extends Component
{
    use HasMenu;

    public $user;

    public $team;

    protected $listeners = ['teamMemberUpdated' => '$refresh'];

    public function mount() {
    }

    public function unassign($user_id) {
        $this->team->users()->detach($user_id);
        $this->emit('teamMemberRemoved');
    }

    public function render()
    {
        return view('livewire.admin.teams.team-member-card');
    }
}
