<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Service;
    use App\Models\Task;
    use App\Models\Team;
    use App\Traits\Slideable;
    use App\Traits\Tasks\Taskable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Taskable, Slideable;

        public function mount()
        {

        }

        public function load()
        {
            if (! $this->client) { // Task create is not on client page. there for get all clients so user can set client.
                $this->clients = Client::where('archived', '0')->orderBy('title', 'asc')->select('title', 'id')->get();
            } else { // Task create is on client page. Get the clients projects and other tasks.
                $this->projects = Project::with('phases:id,step,title')->where('client_id', $this->client->id)->select('title', 'id')->get();
                $this->dependable_tasks = Task::where('client_id', $this->client->id)->select('title', 'id')->get();
            }

            if ($this->team) { // We are on team page
                $this->assign_to_team = true;
                array_push($this->team_ids, $this->team->id);
            }

            $this->assignable_teams = Team::orderBy('title')->select('title', 'id')->get();
        }

        public function rules()
        {
            return [
                'client'  => ['required'],
                'project' => ['required'],
                'phase'   => ['required'],
                'title'   => ['required', 'min:2', 'max:300'],
            ];
        }

        /**
         * Create the new task
         */
        public function createTask()
        {
            $this->validate();

            DB::beginTransaction();

            // Create Task
            $task = Task::create([
                'client_id'          => $this->client->id,
                'project_id'         => $this->project->id,
                'phase_id'           => $this->phase->id,
                'title'              => $this->title,
                'description'        => $this->description,
                'due_date'           => $this->due_date ? Carbon::parse($this->due_date) : null,
                'type'               => $this->task_type,
                'add_file_uploader'  => $this->add_file_uploader,
                'dependable_task_id' => $this->dependable_task ? $this->dependable_task->id : null,
                'priority'           => $this->priority,
                'visible'            => ! $this->hidden,
            ]);

            // Save Task Urls
            $this->saveUrls($task);

            if ($this->team_ids) {
                foreach ($this->team_ids as $id) {
                    $task->teams()->attach($id);
                }
            }

            $action = Action::create([
                'client_id'       => $this->client->id,
                'user_id'         => auth()->user()->id,
                'type'            => 'model_created',
                'value'           => 'task',
                'relation_id'     => $task->id,
                'actionable_type' => 'App\Models\Phase',
                'actionable_id'   => $this->phase->id,
            ]);

            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('taskCreated');
            flash('Task Created!')->success()->livewire($this);
            $this->reset([
                'title',
                'description',
                'due_date',
                'task_type',
                'attach_url',
                'urls',
                'url_labels',
                'add_file_uploader',
                'add_dependable_task',
                'dependable_task',
                'priority',
                'hidden',
            ]);
            $this->mount();
        }

        public function render()
        {
            return view('livewire.admin.tasks.create');
        }
    }
