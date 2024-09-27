<?php

namespace App\Http\Livewire\Client\Teams;

use App\Mail\Welcome;
use App\Models\Action;
use App\Models\Client;
use App\Models\Team;
use App\Models\User;
use App\Traits\HasMenu;
use App\Traits\Users\Userable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserCard extends Component
{
    use HasMenu;
    public $user;

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
        abort_unless($this->user->client_id == auth()->user()->client_id, 403);

        $this->user->delete();

        $this->emit('userDeleted');

        flash('User Deleted')->success()->livewire($this);
    }

    /**
     * Make a user a primary contact for the client
     */
    public function makePrimaryContact() {
        abort_unless($this->user->client_id == auth()->user()->client_id, 403);

        auth()->user()->client->update([
            'primary_contact' => $this->user->id,
        ]);

        // Save action
        $start_date = Carbon::now()->subHour();
        $action = Action::where('type', 'primary_contact_set')
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', Carbon::now())
            ->where('actionable_type', 'App\Models\Client')
            ->where('actionable_id', auth()->user()->client_id)
            ->firstOrNew();

        $action->fill([
            'client_id' => auth()->user()->client_id,
            'user_id' => auth()->user()->id,
            'type' => 'primary_contact_set',
            'relation_id' => $this->user->id,
            'data' => json_encode(['full_name' => $this->user->full_name]),
            'actionable_type' => get_class(auth()->user()->client),
            'actionable_id' => auth()->user()->client_id,
        ]);

        $action->save();

        $this->emit('actionCreated');

        $this->emit('userUpdate');

        $this->emit('primaryContactChanged');

        flash('Primary Contact Set')->success()->livewire($this);
    }


    public function render()
    {
        return view('livewire.client.teams.user-card');
    }
}
