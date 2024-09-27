<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Question;
    use App\Models\Service;
    use App\Models\Tutorial;
    use App\Traits\Slideable;
    use App\Traits\Tutorials\Tutorialable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
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
            if ( $this->setPackage && $this->service ) {
                $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
            }
            if ( $this->client ) { // For single Client page. Add current viewed client to the client_ids array so that this client is preset in the client select.
                array_push($this->client_ids, $this->client->id);
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
         * Create the new question
         */
        public function createTutorial() {
            $this->validate();

            DB::beginTransaction();
            // Create tutorials
            $tutorial = Tutorial::create([
                'title'     => $this->title,
                'body'      => $this->body,
                'video_url' => $this->video_url,
            ]);

            switch ( $this->assign_type ) {
                case('client'):
                    foreach ( $this->client_ids as $id ) {
                        $tutorial->clients()->attach($id);
                        Action::create([
                            'client_id' => $id,
                            'user_id' => auth()->user()->id,
                            'type' => 'model_created',
                            'value' => 'tutorial',
                            'relation_id' => $tutorial->id,
                            'actionable_type' => 'App\Models\Client',
                            'actionable_id' => $id,
                        ]);

                        $this->emit('actionCreated');
                    }
                    break;
                case('service'):
                    $tutorial->services()->attach($this->service);
                    break;
                case('package'):
                    $tutorial->packages()->attach($this->package);
                    break;
            }

            DB::commit();

            $this->reset([
                'title',
                'body',
                'video_url',
                'client_ids',
            ]);

            $this->closeSlideout();
            $this->emit('tutorialCreated');
            flash('Tutorial Created!')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.tutorials.create');
        }
    }
