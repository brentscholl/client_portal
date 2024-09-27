<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Action;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;
    use App\Models\Team;
    use App\Traits\Slideable;
    use App\Traits\Tasks\Taskable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Taskable, Slideable;

        public function mount(Task $task) {
            $this->task = $task;
        }

        public function load() {
            // Set current values
            $this->client = $this->task->client;
            $this->setClient = false;
            $this->project = $this->task->project;
            $this->projects = $this->task->client->projects;
            $this->phase = $this->task->phase;
            $this->title = $this->task->title;
            $this->description = $this->task->description;
            $this->due_date = $this->task->due_date ? $this->task->due_date->format("Y-m-d") : null;
            $this->task_type = $this->task->type;
            $this->attach_url = $this->task->urls->count() > 0 ? '1' : '0';

            $urli = 0; // url count
            foreach ( $this->task->urls as $url ) {
                $this->urls[$urli] = $url->url;
                $this->url_labels[$urli] = $url->label;
                $urli++;
            }

            $this->add_file_uploader = $this->task->add_file_uploader;
            $this->hidden = ! $this->task->visible;
            $this->add_dependable_task = $this->task->dependable_task_id != null ? '1' : '0';
            $this->dependable_task = Task::find($this->task->dependable_task_id);
            $this->dependable_tasks = Task::where('client_id', $this->task->client_id)->where('id', '!=', $this->task->id)->get();
            $this->priority = $this->task->priority;

            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();
            // Get teams attached to question and set them
            if ( $this->task->teams->count() > 0 ) {
                $this->assign_to_team = true;
                foreach ( $this->task->teams as $team ) {
                    array_push($this->team_ids, $team->id);
                }
            }
        }

        public function rules() {
            return [
                'project.id' => ['required'],
                'phase.id'   => ['required'],
                'title'      => ['required', 'min:2', 'max:300'],
            ];
        }

        /**
         * Update the task
         */
        public function saveTask() {
            $this->validate();

            // Check what is being updated for action
            $values = [
                'Assigned Phase' => $this->task->phase_id !== $this->phase->id,
                'Title' => $this->task->title !== $this->title,
                'Description' => $this->task->description !== $this->description,
                'Due Date' => Carbon::parse($this->task->due_date)->format('Y-M-D') !== Carbon::parse($this->due_date)->format('Y-M-D'),
                'Task Type' => $this->task->type !== $this->task_type,
                'Priority' => $this->task->priority !== $this->priority,
            ];

            $value_string = formatListForSentence($values);

            DB::beginTransaction();

            // Update task
            $this->task->update([
                'project_id'         => $this->project->id,
                'phase_id'           => $this->phase->id,
                'title'              => $this->title,
                'description'        => $this->description,
                'due_date'           => $this->due_date ? Carbon::parse($this->due_date) : null,
                'type'               => $this->task_type,
                'add_file_uploader'  => $this->add_file_uploader,
                'dependable_task_id' => $this->dependable_task ? $this->dependable_task->id : null,
                'priority'           => $this->priority,
            ]);

            // Delete old URLs
            foreach ( $this->task->urls as $url ) {
                $url->delete();
            }

            // Add new URLs
            $this->saveUrls($this->task);

            // Attach updated teams
            $this->task->teams()->detach(); // Detach regardless.
            if ( $this->assign_to_team ) {
                foreach ( $this->team_ids as $id ) {
                    $this->task->teams()->attach($id);
                }
            }

            // Save action
            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'model_updated')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('actionable_type', 'App\Models\Task')
                ->where('actionable_id', $this->task->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->task->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'model_updated',
                'value' => $value_string,
                'actionable_type' => get_class($this->task),
                'actionable_id' => $this->task->id,
                ]);

            $action->save();

            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('taskUpdated');
            flash('Task Updated!')->success()->livewire($this);
            $this->mount($this->task);
        }

        public function render() {
            return view('livewire.admin.tasks.edit');
        }
    }
