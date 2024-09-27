<?php

    namespace App\Http\Livewire\Admin\Teams;

    use App\Models\Client;
    use App\Models\Team;
    use App\Models\User;
    use App\Traits\Collapsible;
    use Livewire\Component;

    class TeamMembers extends Component
    {
        use Collapsible;

        public $team;

        public $listeners = ['teamMemberUpdated' => '$refresh', 'teamMemberAdded' => '$refresh', 'teamMemberRemoved' => '$refresh'];

        public function mount(Team $team) {
            $this->team = $team;
        }

        public function render() {
            return view('livewire.admin.teams.team-members');
        }
    }
