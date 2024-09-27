<?php

    namespace App\Http\Livewire\Admin\Users;

    use App\Mail\Welcome;
    use App\Models\Client;
    use App\Models\Team;
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

        public function mount()
        {

        }

        public function load()
        {
            if (! $this->client) {
                $this->clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            }
            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();
        }

        public function rules()
        {
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

        /**
         * Custom Validation Messages
         *
         * @var string[]
         */
        protected $messages = [
            'client.required_if' => 'An assigned Client is required if the user type is Basic',
        ];

        public function updatePhone($value)
        {
            $this->phone = PhoneNumber::make($value);
        }

        /**
         * Create a new user
         * todo: add verification email
         * todo: add roles
         */
        public function addUser()
        {
            $this->validate();

            DB::beginTransaction();

            $user = User::create([
                'client_id' => $this->user_type == 'basic' ? $this->client->id : null,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'position' => $this->position,
            ]);

            if ($this->email_new_user) {
                // Email the new user Welcome message.
                \Mail::to($user)->queue(new Welcome($user, $this->password));
            }

            if ($this->user_type == 'admin' && $this->team_ids) {
                foreach ($this->team_ids as $id) {
                    $user->teams()->attach($id);
                }
            }

            // Make user primary contact if they are the first user to be added to client
            if ($this->user_type == 'basic') {
                if (! $this->client->primaryContact) {
                    Client::find($this->client->id)->update(['primary_contact' => $user->id]);
                }
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('userCreated');
            flash('User Created!')->success()->livewire($this);
            $this->reset([
                'first_name',
                'last_name',
                'email',
                'password',
                'phone',
                'position',
            ]);
            $this->dispatchBrowserEvent('dom_updated');
        }

        public function render()
        {
            return view('livewire.admin.users.create');
        }
    }
