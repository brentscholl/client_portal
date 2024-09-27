<?php

    namespace App\Http\Livewire\Admin\Phases;

    use App\Models\Action;
    use App\Models\Phase;
    use App\Traits\Phases\Phaseable;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Phaseable, Slideable;

        public function mount() {

        }

        public function load() {

        }

        public function rules() {
            return [
                'title'    => ['required', 'string'],
                'due_date' => ['sometimes'],
                'project'  => ['required'],
            ];
        }

        /**
         * Create the new phase
         */
        public function createPhase() {
            $this->validate();

            DB::beginTransaction();

            // Count the phases so we know what order this new phase should be.
            $phaseCount = Phase::where('project_id', $this->project->id)->count();

            // Create phase
            $phase = Phase::create([
                'client_id'   => $this->project->client_id,
                'project_id'  => $this->project->id,
                'step'        => $phaseCount + 1,
                'title'       => $this->title,
                'description' => $this->description,
                'due_date'    => $this->due_date ? Carbon::parse($this->due_date) : null,
            ]);

            // Save urls
            $this->saveUrls($phase);

            $action = Action::create([
                'client_id' => $this->project->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'model_created',
                'value' => 'phase',
                'relation_id' => $phase->id,
                'actionable_type' => 'App\Models\Project',
                'actionable_id' => $this->project->id,
            ]);

            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('phaseCreated');
            flash('Phase Created!')->success()->livewire($this);
            $this->reset([
                'title',
                'description',
                'due_date',
            ]);
        }

        public function render() {
            return view('livewire.admin.phases.create');
        }
    }
