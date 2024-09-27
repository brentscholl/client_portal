<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Client;
    use App\Traits\Clients\Clientable;
    use App\Traits\RealTimeValidation;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;
    use Livewire\WithFileUploads;

    class Create extends Component
    {
        use Clientable, WithFileUploads, Slideable;

        public function mount() {

        }

        public function load() {

        }

        public function rules() {
            return [
                'title'        => ['required'],
                'website_url'    => ['string', 'nullable'],
                'monthly_budget' => ['numeric', 'nullable'],
                'annual_budget'  => ['numeric', 'nullable'],
                'newAvatar'      => [],
            ];
        }

        /**
         * Create the new client
         *todo: validate image
         */
        public function addClient() {
            $this->validate();

            DB::beginTransaction();

            // Create the client
            $client = Client::create([
                'title'        => $this->title,
                'website_url'    => $this->website_url,
                'monthly_budget' => $this->monthly_budget,
                'annual_budget'  => $this->annual_budget,
            ]);

            // Store Avatar if present
            if ( $this->newAvatar ) {
                $filename = $this->newAvatar->store('/', 'avatars');

                $client->avatar = $filename;
                $client->save();
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('clientCreated');
            flash('Client Created!')->success()->livewire($this);
            $this->reset();
        }

        public function render() {
            return view('livewire.admin.clients.create');
        }
    }
