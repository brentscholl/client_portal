<?php

    namespace App\Http\Livewire\Admin\Users;

    use App\Models\Client;
    use App\Models\Team;
    use App\Models\User;
    use App\Traits\Slideable;
    use App\Traits\Users\Userable;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Livewire\Component;

    class Edit extends Component
    {
        use Userable, Slideable;

        public function mount() {
        }

        public function load() {
            $this->client = $this->user->client;
            $this->setClient = false;
            $this->first_name = $this->user->first_name;
            $this->last_name = $this->user->last_name;
            $this->email = $this->user->email;
            $this->phone = $this->user->phone;
            $this->position = $this->user->position;
            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();
            $this->clients = Client::orderBy('title', 'asc')->get();
            $this->user_type = $this->user->client_id ? 'basic' : 'admin';

            if ( $this->user->teams ) {
                foreach ( $this->user->teams as $team ) {
                    array_push($this->team_ids, $team->id);
                }
            }
        }

        public function rules() {
            return [
                'first_name' => ['required', 'string', 'min:2', 'max:20'],
                'last_name'  => ['required', 'string', 'min:2', 'max:20'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id . ',id,deleted_at,NULL'],
                'password'   => ['nullable', 'string', 'min:6'],
                'phone'      => ['nullable', 'min:10', 'max:20', 'phone:AUTO,CA,US'],
                'position'   => ['nullable', 'string', 'min:2', 'max:40'],
                'client.id'  => ['required_if:user_type, basic'],
            ];
        }

        /**
         * Custom Validation Messages
         *
         * @var string[]
         */
        protected $messages = [
            'client.id.required_if' => 'A client is required if the user type is basic',
        ];

        /**
         * Update the user
         */
        public function saveUser() {
            $this->validate();

            DB::beginTransaction();

            // Update user
            $this->user->update([
                'client_id'  => $this->user_type == 'basic' ? $this->client->id : null,
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'email'      => $this->email,
                'phone'      => $this->phone,
                'position'   => $this->position,
            ]);

            // Update password if new password was entered
            if ( $this->password != '' ) {
                $this->user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $this->user->teams()->detach();
            if($this->user_type == 'admin' && $this->team_ids){
                foreach ( $this->team_ids as $id ) {
                    $this->user->teams()->attach($id);
                }
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('userUpdated');
            flash('User Updated!')->success()->livewire($this);
            $this->mount();
        }

        public function render() {
            return view('livewire.admin.users.edit');
        }
    }
