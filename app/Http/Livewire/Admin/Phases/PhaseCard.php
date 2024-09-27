<?php

    namespace App\Http\Livewire\Admin\Phases;

    use App\Models\Phase;
    use App\Traits\HasMenu;
    use Livewire\Component;

    class PhaseCard extends Component
    {
        use HasMenu;

        public $phase;

        public $last;

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

        /**
         * Delete the phase
         */
        public function destroy() {
            // Delete the phase
            $this->phase->delete();
            $this->emit('phaseDeleted');
            flash('Phase Deleted')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.phases.phase-card');
        }
    }
