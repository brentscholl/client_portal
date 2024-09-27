<?php

    namespace App\Http\Livewire\Admin\Teams;

    use App\Models\User;
    use Livewire\Component;

    class AddMember extends Component
    {
        public $team;

        public $assign_marketing_advisor = false;

        public $assignable_users = [];

        public $assignee_ids = [];

        public $modalOpen = false;

        public $allow_assign = true;

        public function mount() {
        }

        public function load() {
            $this->assignable_users = User::admin()->orderBy('first_name', 'asc')->get();

            $this->assignee_ids = $this->team->users->pluck('id')->toArray();
            $this->modalOpen = true;
        }

        public function assign($user_id) {
            $this->team->users()->attach($user_id);
            array_push($this->assignee_ids, $user_id);
            $this->emit('teamMemberAdded');
        }

        public function render() {
            return view('livewire.admin.teams.add-member');
        }
    }
