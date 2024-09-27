<?php

    namespace App\Http\Livewire\Settings;

    use App\Traits\RealTimeValidation;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Livewire\Component;
    use Livewire\WithFileUploads;

    class General extends Component
    {
        use WithFileUploads, RealTimeValidation;

        public $first_name;

        public $last_name;

        public $email;

        public $phone;

        public $position;

        public $upload;

        public $newAvatar;

        public $inlineEditing = false;

        public function mount() {
            $user = auth()->user();
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->position = $user->position;
        }

        public function rules() {
            return [
                'first_name' => ['required', 'string', 'min:2', 'max:20'],
                'last_name'  => ['required', 'string', 'min:2', 'max:20'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->id . ',id,deleted_at,NULL'],
                'phone'      => ['min:10', 'max:20', 'phone:AUTO,CA,US'],
                'position'   => ['nullable', 'string', 'min:2', 'max:40'],
            ];
        }

        public function save() {
            $this->validate();

            $user = auth()->user();
            $user->first_name = Str::ucfirst($this->first_name);
            $user->last_name = Str::ucfirst($this->last_name);
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->position = Str::ucfirst($this->position);
            $user->save();

            $this->emitSelf('notify-saved');
        }

        /**
         * todo: Validate image
         */
        public function saveImage() {
            // validate
            // $this->validate([
            //     'newAvatar' => ['image', 'max:5000']
            // ]);

            if ( auth()->user()->avatar ) {
                Storage::disk('avatars')->delete(auth()->user()->avatar);
                auth()->user()->avatar = null;
            }

            $filename = $this->newAvatar->store('/', 'avatars');

            auth()->user()->avatar = $filename;
            auth()->user()->save();
            $this->emitSelf('notify-saved');
        }

        public function removeAvatar() {
            Storage::disk('avatars')->delete(auth()->user()->avatar);
            auth()->user()->update([
                'avatar' => null,
            ]);
        }

        public function cancel() {
            $this->resetErrorBag();
            $this->reset(['first_name', 'last_name', 'email', 'phone', 'position']);
            $this->mount();
        }

        public function render() {
            return view('livewire.settings.general');
        }
    }
