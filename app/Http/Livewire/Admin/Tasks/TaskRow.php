<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Task;
    use App\Traits\HasMenu;
    use Livewire\Component;

    class TaskRow extends Component
    {
        use HasMenu;

        public $task;

        protected $listeners = ['taskUpdated' => '$refresh'];

        public function mount(Task $task)
        {
            $this->task = $task;
        }

        public function toggleVisibility() {
            $this->task->update([
                'visible' => ! $this->task->visible
            ]);
            $this->emit('taskUpdated');
            $this->mount($this->task);
        }

        /**
         * Delete Task
         */
        public function destroy() {
            // Delete Task
            $this->task->delete();
            $this->emit('taskDeleted');
            flash('Task Deleted')->success()->livewire($this);
        }

        public function render()
        {
            return view('livewire.admin.tasks.task-row');
        }
    }
