<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Service;
    use App\Models\Team;
    use App\Traits\Questions\Questionable;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Questionable, Slideable;

        public function mount() {
        }

        public function load() {
            // Set available values for selections
            if ( $this->setClient ) {
                $this->clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            }

            if ( $this->client && $this->setProject ) {
                $this->projects = Project::with('phases:id,step,title')->where('client_id', $this->client->id)->select('title', 'id')->get();
            }

            if ( $this->setService ) {
                $this->assignable_services = Service::orderBy('title')->select('title', 'slug', 'id')->get();
            }

            if ( $this->service ) {
                array_push($this->service_ids, $this->service->id);
                if ( count($this->service_ids) > 1 ) {
                    $this->single_service = false;
                }
                if ( $this->setPackage ) {  // can only get a package if we know what service is set
                    $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
                }
            }

            if($this->setService || $this->setPackage){
                $this->services = Service::orderBy('title')->select('title', 'slug', 'id')->get();
            }

            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();

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
         * Create the new question
         */
        public function createQuestion() {
            $this->validate();

            DB::beginTransaction();

            $isChoice = false;
            if ( $this->question_type == 'multi_choice' || $this->question_type == 'select' ) {
                $isChoice = true;
            }

            // Create question
            $question = Question::create([
                'body'              => $this->body,
                'tagline'           => $this->tagline,
                'type'              => $this->question_type,
                'choices'           => $isChoice ? json_encode($this->choices) : null,
                'add_file_uploader' => $this->add_file_uploader,
            ]);

            // Attach relationships
            switch ( $this->assign_type ) {
                case('client'):
                    $question->clients()->attach($this->client);
                    Action::create([
                        'client_id' => $this->client->id,
                        'user_id' => auth()->user()->id,
                        'type' => 'model_created',
                        'value' => 'question',
                        'relation_id' => $question->id,
                        'actionable_type' => get_class($this->client),
                        'actionable_id' => $this->client->id,
                    ]);

                    $this->emit('actionCreated');
                    break;
                case('project'):
                    $question->projects()->attach($this->project);
                    Action::create([
                        'client_id' => $this->project->client_id,
                        'user_id' => auth()->user()->id,
                        'type' => 'model_created',
                        'value' => 'question',
                        'relation_id' => $question->id,
                        'actionable_type' => get_class($this->project),
                        'actionable_id' => $this->project->id,
                    ]);

                    $this->emit('actionCreated');
                    break;
                case('service'):
                    foreach ( $this->service_ids as $id ) {
                        $question->services()->attach($id);
                    }
                    break;
                case('package'):
                    $question->packages()->attach($this->package);
                    break;
                case('onboarding'):
                    $question->is_onboarding = true;
                    $question->save();
                    break;
            }

            if($this->team_ids && $this->assign_to_team){
                foreach ( $this->team_ids as $id ) {
                    $question->teams()->attach($id);
                }
            }

            DB::commit();

            $this->closeSlideout();
            $this->emit('questionCreated');
            flash('Question Created!')->success()->livewire($this);

            $this->reset([
                'body',
                'tagline',
                'assign_type',
                'question_type',
                'choices',
                'add_file_uploader',
            ]);
        }

        public function render() {
            return view('livewire.admin.questions.create');
        }
    }
