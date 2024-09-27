<?php

    namespace App\Http\Livewire\Admin\Phases;

    use App\Models\Phase;
    use Livewire\Component;

    class Show extends Component
    {
        public $phase;

        protected $listeners = ['phaseUpdated' => '$refresh', 'taskUpdated' => '$refresh'];

        public function mount(Phase $phase) {
            $this->phase = $phase;
        }

        public function toggleVisibility() {
            $this->phase->update([
                'visible' => ! $this->phase->visible
            ]);
            $this->emit('phaseUpdated');
            $this->mount($this->phase);
        }

        public function destroy() {
            $phase_project_id = $this->phase->project_id;
            $this->phase->delete();
            flash('Phase Deleted')->success();
            return redirect()->route('admin.projects.show', $phase_project_id);
        }

        public function render() {
            return view('livewire.admin.phases.show');
        }
    }
