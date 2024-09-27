<?php

    namespace App\Http\Livewire\Settings;

    use App\Traits\RealTimeValidation;
    use Illuminate\Support\Facades\Hash;
    use Livewire\Component;

    class Password extends Component
    {
        use RealTimeValidation;

        public $current_password;

        public $new_password;

        public $new_password_confirmation;

        public function rules() {
            return [
                'current_password'          => ['required'],
                'new_password'              => ['nullable', 'string', 'min:6'],
                'new_password_confirmation' => ['required', 'string', 'same:new_password'],
            ];
        }

        public function updatedCurrentPassword() {
            if ( ! Hash::check($this->current_password, auth()->user()->password) ) {
                $this->addError('current_password', 'This password is incorrect.');
            }
        }

        public function save() {
            $this->validate();

            if ( Hash::check($this->current_password, auth()->user()->password) ) {
                auth()->user()->update([
                    'password' => Hash::make($this->new_password),
                ]);
                $this->emitSelf('notify-saved');
                $this->reset();
            } else {
                $this->addError('current_password', 'This password is incorrect.');
            }
        }

        public function render() {
            return view('livewire.settings.password');
        }
    }
