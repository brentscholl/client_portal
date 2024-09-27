<?php

    namespace App\Http\Livewire\Admin\Phases;

    use App\Models\Action;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Traits\Collapsible;
    use Illuminate\Support\Carbon;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $project;

        public $perPage = 10;

        public $listeners = ['phaseUpdated' => '$refresh', 'phaseCreated' => '$refresh', 'phaseDeleted' => '$refresh'];

        public function mount(Project $project) {
            $this->project = $project;
        }

        /**
         * Change the order of the phase
         * (on the drag and drop)
         * @param $list
         */
        public function updatePhaseOrder($list) {
            foreach ( $list as $item ) {
                Phase::find($item['value'])->update(['step' => $item['order']]);
            }
            $this->emitSelf('phaseUpdated');

            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'phase_reordered')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('actionable_type', 'App\Models\Project')
                ->where('actionable_id', $this->project->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->project->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'phase_reordered',
                'actionable_type' => get_class($this->project),
                'actionable_id' => $this->project->id,
            ]);

            $action->save();


            $this->emit('actionCreated');
        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 10;
        }

        public function render() {
            $phases = Phase::with(['tasks'])->where('project_id', $this->project->id)->paginate($this->perPage);
            return view('livewire.admin.phases.index-card', ['phases' => $phases]);
        }
    }
