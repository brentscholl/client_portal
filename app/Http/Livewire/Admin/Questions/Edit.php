<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Service;
    use App\Models\Team;
    use App\Traits\Questions\Questionable;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Questionable, Slideable;

        public function mount() {

        }

        public function load() {
            // Set available values for selections
            if ( $this->setClient ) {
                $this->clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            }

            if ( $this->setService ) {
                $this->assignable_services = Service::orderBy('title')->select('title', 'slug', 'id')->get();
            }

            if ( $this->service ) {
                array_push($this->service_ids, $this->service->id);

                if ( count($this->service_ids) > 1 ) {
                    $this->single_service = false;
                }

                if ( $this->setPackage ) { // can only get a package if we know what service is set
                    $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
                }
            }

            if ( $this->setService || $this->setPackage ) {
                $this->services = Service::orderBy('title')->select('title', 'slug', 'id')->get();
            }

            // Set Current Values
            $this->client = $this->question->clients->first();
            $this->body = $this->question->body;
            $this->tagline = $this->question->tagline;
            $this->question_type = $this->question->type;
            $this->choices = json_decode($this->question->choices);
            $this->add_file_uploader = $this->question->add_file_uploader;

            // Set assign type
            if ( $this->question->clients->count() > 0 ) {
                $this->assign_type = 'client';
            } elseif ( $this->question->services->count() > 0 ) {
                $this->assign_type = 'service';
            } elseif ( $this->question->packages->count() > 0 ) {
                $this->assign_type = 'package';
                $this->service = $this->question->packages()->first()->service;
                $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
            } elseif ( $this->question->projects->count() > 0 ) {
                $this->assign_type = 'project';
                $this->client = $this->question->projects()->first()->client;
                $this->project = $this->question->projects()->first();
                $this->projects = Project::where('client_id', $this->client->id)->orderBy('title', 'asc')->select('title', 'id')->get();
            }

            if ( ! $this->question->clients->count() > 0 ) { // Question does not belong to a client
                // Get the services attached to the question and set them.
                foreach ( $this->question->services as $service ) {
                    array_push($this->service_ids, $service->id);
                }
                // Get the packages attached to the question and set them.
                if ( optional($this->question->packages)->count() > 0 ) {
                    $this->package = $this->question->packages->first();
                }
            }

            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();
            // Get teams attached to question and set them
            if ( $this->question->teams->count() > 0 ) {
                $this->assign_to_team = true;
                foreach ( $this->question->teams as $team ) {
                    array_push($this->team_ids, $team->id);
                }
            }
        }

        public function rules() {
            return [
                'client'        => ['required_if:assign_type,client'],
                'service_ids'   => ['required_if:assign_type,service'],
                'package'       => ['required_if:assign_type,package'],
                'body'          => ['required', 'string', 'min:6', 'max:1000'],
                'question_type' => ['required'],
                'choices'       => ['sometimes', 'required_if:question_type,multi_choice', 'required_if:question_type,select', 'max:200'],
            ];
        }

        /**
         * Custom Validation Messages
         *
         * @var string[]
         */
        protected $messages = [
            'service_ids.required_if' => 'A service is required if the assign type is service',
        ];

        /**
         * Update the question
         */
        public function saveQuestion() {
            $this->validate();

            DB::beginTransaction();

            $isChoice = false;
            if ( $this->question_type == 'multi_choice' || $this->question_type == 'select' ) {
                $isChoice = true;
            }

            // Update question
            $this->question->update([
                'body'              => $this->body,
                'tagline'           => $this->tagline,
                'type'              => $this->question_type,
                'choices'           => $isChoice ? json_encode($this->choices) : null,
                'add_file_uploader' => $this->add_file_uploader,
            ]);

            // Detach old relationships and attach updated relationships
            $this->question->is_onboarding = false;
            switch ( $this->assign_type ) {
                case('client'):
                    $this->question->clients()->detach();
                    $this->question->clients()->attach($this->client);
                    break;
                case('project'):
                    $this->question->projects()->detach();
                    $this->question->projects()->attach($this->project);
                    break;
                case('service'):
                    $this->question->services()->detach();
                    foreach ( $this->service_ids as $id ) {
                        $this->question->services()->attach($id);
                    }
                    break;
                case('package'):
                    $this->question->packages()->detach();
                    $this->question->packages()->attach($this->package);
                    break;
                case('onboarding'):
                    $this->question->is_onboarding = true;
                    break;
            }
            $this->question->save();

            // Attach updated teams
            $this->question->teams()->detach(); // Detach regardless.
            if ( $this->assign_to_team ) {
                foreach ( $this->team_ids as $id ) {
                    $this->question->teams()->attach($id);
                }
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('questionUpdated');
            flash('Question Updated!')->success()->livewire($this);

            $this->mount();
        }

        public function render() {
            return view('livewire.admin.questions.edit');
        }
    }
