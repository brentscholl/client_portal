<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Service;
    use App\Models\Tutorial;
    use App\Traits\Tutorials\Tutorialable;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Tutorialable, Slideable;

        public function mount() {
        }

        public function load() {
            if ( $this->setClient ) {
                $this->assignable_clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            }
            if ( $this->setService ) {
                $this->services = Service::orderBy('title')->select('title', 'slug', 'id')->get();
            }
            if ( $this->service && $this->setPackage ) {
                $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
            }
            if ( $this->tutorial->clients ) { // Add all clients attached to tutorial to client_ids array so they are shown in the client select.
                foreach ( $this->tutorial->clients as $client ) {
                    array_push($this->client_ids, $client->id);
                }
            }

            // Set current values
            $this->service = $this->tutorial->services->first();
            $this->package = $this->tutorial->packages->first();
            $this->title = $this->tutorial->title;
            $this->body = $this->tutorial->body;
            $this->video_url = $this->tutorial->video_url;

            if ( $this->tutorial->clients->count() > 0 ) { // For clients
                $this->assign_type = 'client';
            } elseif ( $this->tutorial->services->count() > 0 ) { // For service
                $this->assign_type = 'service';
            } elseif ( $this->tutorial->packages->count() > 0 ) { // For package
                $this->assign_type = 'package';
                $this->service = $this->tutorial->packages()->first()->service;
                $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
            }
        }

        public function rules() {
            return [
                'client_ids' => ['required_if:assign_type,client'],
                'service'    => ['required_if:assign_type,service'],
                'title'      => ['required', 'string', 'min:2', 'max:100'],
                'body'       => ['nullable', 'string', 'min:0', 'max:1000'],
                'video_url'  => ['required', 'url'],
            ];
        }

        /**
         * Custom Validation Messages
         *
         * @var string[]
         */
        protected $messages = [
            'client_ids.required_if' => 'A client is required if the assign type is client',
        ];

        /**
         * Update the tutorial
         */
        public function saveTutorial() {
            $this->validate();

            DB::beginTransaction();

            // Create tutorials
            $this->tutorial->update([
                'title'     => $this->title,
                'body'      => $this->body,
                'video_url' => $this->video_url,
            ]);

            // Detach old relationships and attach new
            $this->tutorial->clients()->detach();
            $this->tutorial->services()->detach();
            $this->tutorial->packages()->detach();

            switch ( $this->assign_type ) {
                case('client'):
                    foreach ( $this->client_ids as $id ) {
                        $this->tutorial->clients()->attach($id);
                    }
                    break;
                case('service'):
                    $this->tutorial->services()->attach($this->service);
                    break;
                case('package'):
                    $this->tutorial->packages()->attach($this->package);
                    break;
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('tutorialUpdated');
            flash('Tutorial Updated!')->success()->livewire($this);
            $this->mount();
        }

        public function render() {
            return view('livewire.admin.tutorials.edit');
        }
    }
