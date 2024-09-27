<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Action;
    use App\Models\User;
    use App\Traits\HasMenu;
    use App\Traits\Impersonateable;
    use Illuminate\Support\Carbon;
    use Livewire\Component;

    class RepCard extends Component
    {
        use HasMenu, Impersonateable;

        public $user;

        public $client;

        protected $listeners = ['userUpdated' => '$refresh', 'primaryContactChanged' => '$refresh'];

        public function mount(User $user) {
            $this->user = $user;
        }

        /**
         * Delete the user
         * todo: properly delete user
         * todo: Should just be unassign from client. not delete.
         */
        public function destroy() {

            $this->user->delete();

            $this->emit('userDeleted');

            flash('User Deleted')->success()->livewire($this);
        }

        /**
         * Make a user a primary contact for the client
         */
        public function makePrimaryContact() {
            $this->client->update([
                'primary_contact' => $this->user->id,
            ]);

            // Save action
            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'primary_contact_set')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('actionable_type', 'App\Models\Client')
                ->where('actionable_id', $this->client->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->client->id,
                'user_id' => auth()->user()->id,
                'type' => 'primary_contact_set',
                'relation_id' => $this->user->id,
                'data' => json_encode(['full_name' => $this->user->full_name]),
                'actionable_type' => get_class($this->client),
                'actionable_id' => $this->client->id,
            ]);

            $action->save();

            $this->emit('actionCreated');

            $this->emit('userUpdate');

            $this->emit('primaryContactChanged');

            flash('Primary Contact Set')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.clients.rep-card');
        }
    }
