<?php

    namespace App\Http\Livewire\Client\Teams;

    use App\Mail\Welcome;
    use App\Models\Client;
    use App\Models\User;
    use App\Traits\Slideable;
    use App\Traits\Users\Userable;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Livewire\Component;
    use Propaganistas\LaravelPhone\PhoneNumber;

    class Create extends Component
    {
        use Userable, Slideable;

        public $is_editing = false;

        public function mount(User $user)
        {
            $this->client = auth()->user()->client;
        }

        public function load()
        {
            if($this->is_editing && $this->user){
                $this->first_name = $this->user->first_name;
                $this->last_name = $this->user->last_name;
                $this->email = $this->user->email;
                $this->email_confirmation = $this->user->email;
                $this->phone = $this->user->phone;
                $this->position = $this->user->position;
            }
        }

        public function rules()
        {
            if($this->is_editing){
                return [
                    'first_name' => ['required', 'string', 'min:2', 'max:20'],
                    'last_name'  => ['required', 'string', 'min:2', 'max:20'],
                    'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id . ',id,deleted_at,NULL'],
                    'password'   => ['nullable', 'string', 'min:6'],
                    'phone'      => ['nullable', 'min:10', 'max:20', 'phone:AUTO,CA,US'],
                    'position'   => ['nullable', 'string', 'min:2', 'max:40'],
                    'client.id'  => ['required_if:user_type, basic'],
                ];
            }else {
                return [
                    'first_name' => ['required', 'string', 'min:2', 'max:20'],
                    'last_name'  => ['required', 'string', 'min:2', 'max:20'],
                    'email'      => [
                        'required',
                        'confirmed',
                        'string',
                        'email',
                        'max:255',
                        'unique:users,email,'.auth()->user()->id.',id,deleted_at,NULL',
                    ],
                    'password'   => ['required', 'string', 'min:6'],
                    'phone'      => ['nullable', 'min:10', 'max:20', 'phone:AUTO,CA,US'],
                    'position'   => ['nullable', 'string', 'min:2', 'max:40'],
                    'client'     => ['required_if:user_type,basic'],
                ];
            }
        }

        public function updatePhone($value)
        {
            $this->phone = PhoneNumber::make($value);
        }

        /**
         * Create a new user
         * todo: add verification email
         * todo: add roles
         */
        public function createUser()
        {
            $this->validate();

            DB::beginTransaction();

            if($this->is_editing && $this->user){
                // Update user
                $this->user->update([
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
                flash('User Updated!')->success()->livewire($this);
                $this->emit('userUpdated');
            } else {
                $user = User::create([
                    'client_id'  => $this->client->id,
                    'first_name' => $this->first_name,
                    'last_name'  => $this->last_name,
                    'email'      => $this->email,
                    'password'   => Hash::make($this->password),
                    'phone'      => $this->phone,
                    'position'   => $this->position,
                ]);

                if ($this->email_new_user) {
                    // Email the new user Welcome message.
                    \Mail::to($user)->queue(new Welcome($user, $this->password));
                }

                // Make user primary contact if they are the first user to be added to client
                if (! $this->client->primaryContact) {
                    Client::find($this->client->id)->update(['primary_contact' => $user->id]);
                }
                flash('User Created!')->success()->livewire($this);
                $this->emit('userCreated');
                $this->reset([
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'phone',
                    'position',
                ]);

            }

            DB::commit();

            $this->closeSlideout();

            $this->dispatchBrowserEvent('dom_updated');
        }

        public function render()
        {
            return view('livewire.client.teams.create');
        }
    }
