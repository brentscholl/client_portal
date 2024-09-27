<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Service;
    use App\Traits\Projects\Projectable;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Projectable, Slideable;

        public function mount()
        {

        }

        public function load()
        {
            // Set available selection values
            if (! $this->client) {
                $this->clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            }

            $this->services = Service::orderBy('title')->get();

            if ($this->service && $this->setPackage) {
                $this->assignable_packages = Package::where('service_id', $this->service->id)->select('service_id', 'id', 'title')->get();
            }
        }

        public function rules()
        {
            return [
                'client'   => ['required'],
                'title'    => ['required', 'string'],
                'due_date' => ['sometimes'],
                'service'  => ['required'],
            ];
        }

        /**
         * Create the new project
         */
        public function createProject()
        {
            $this->validate();

            DB::beginTransaction();

            // Create project
            $project = Project::create([
                'client_id'   => $this->client->id,
                'title'       => $this->title,
                'description' => $this->description,
                'service_id'  => $this->service->id,
                'due_date'    => $this->due_date ? Carbon::parse($this->due_date) : null,
                'visible'     => ! $this->hidden,
            ]);

            // Attach service to client without creating duplicates
            $this->client->services()->syncWithoutDetaching($this->service->id);

            // Attache packages
            if ($this->package_ids) {
                foreach ($this->package_ids as $id) {
                    $project->packages()->attach($id);
                }
            }

            // Create First Phase as a default
            Phase::create([
                'client_id'  => $project->client_id,
                'project_id' => $project->id,
                'step'       => '1',
                'title'      => '1',
            ]);

            // Save urls
            $this->saveUrls($project);

            $action = Action::create([
                'client_id'       => $project->client_id,
                'user_id'         => auth()->user()->id,
                'type'            => 'model_created',
                'value'           => 'project',
                'relation_id'     => $project->id,
                'actionable_type' => 'App\Models\Client',
                'actionable_id'   => $project->client_id,
            ]);

            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('projectCreated');
            flash('Project Created!')->success()->livewire($this);
            $this->reset([
                'title',
                'description',
                'service',
                'due_date',
            ]);
        }

        public function render()
        {
            return view('livewire.admin.projects.create');
        }
    }
