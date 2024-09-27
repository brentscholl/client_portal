<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\Impersonateable;
use Livewire\Component;

class Show extends Component
{
    use Impersonateable;
    public $user;


    protected $listeners = ['userUpdated' => '$refresh'];

    public function mount(User $user) {
        $this->user = $user;
    }

    public function destroy() {
        $this->user->delete();
        flash('User Deleted')->success();
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.show');
    }
}
