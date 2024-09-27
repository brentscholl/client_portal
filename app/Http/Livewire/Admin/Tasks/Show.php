<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Task;
    use Livewire\Component;

    class Show extends Component
    {
        public $task;

        protected $listeners = ['taskUpdated' => '$refresh'];

        public function mount(Task $task) {
            $this->task = $task;
        }

        public function toggleVisibility() {
            $this->task->update([
                'visible' => ! $this->task->visible
            ]);
            $this->emit('taskUpdated');
            $this->mount($this->task);
        }

        public function destroy() {
            $task_phase_id = $this->task->phase_id;
            $this->task->delete();
            flash('Task Deleted')->success();
            return redirect()->route('admin.phases.show', $task_phase_id);
        }

        public function render() {
            return view('livewire.admin.tasks.show');
        }
    }
