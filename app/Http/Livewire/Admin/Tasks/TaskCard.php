<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;
    use App\Models\TaskUrl;
    use App\Traits\HasMenu;
    use App\Traits\Tasks\Taskable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class TaskCard extends Component
    {
        use HasMenu;

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

        /**
         * Delete Task
         */
        public function destroy() {
            // Delete Task
            $this->task->delete();
            $this->emit('taskDeleted');
            flash('Task Deleted')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.tasks.task-card');
        }
    }
