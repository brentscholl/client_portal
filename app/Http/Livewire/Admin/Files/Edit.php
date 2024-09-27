<?php

    namespace App\Http\Livewire\Admin\Files;

    use App\Models\Answer;
    use App\Models\Client;
    use App\Models\File;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Slideable;

        public $file;

        public $assign_type;

        public $src;

        public $caption;

        public $project;

        public $projects;

        public $phase;

        public $task;

        public $tasks;

        public $answer;

        public $answers;

        public $client;

        public $clients;

        public $button_type = 'icon';

        public function load()
        {
            $this->clients = Client::orderBy('title', 'asc')->get();
            $this->src = $this->file->src;
            $this->caption = $this->file->caption;
            if ($this->file->tasks()->count() > 0) {
                $this->assign_type = 'task';
                $this->client = $this->file->tasks()->first()->client;
                $this->projects = $this->client->projects;
                $this->project = $this->file->tasks()->first()->project;
                $this->phases = $this->project->phases;
                $this->phase = $this->file->tasks()->first()->phase;
                $this->tasks = $this->phase->tasks;
                $this->task = $this->file->tasks()->first();
            } elseif ($this->file->answers()->count() > 0) {
                $this->assign_type = 'answer';
                $this->client = $this->file->answers()->first()->client;
                $this->answers = $this->file->answers()->first()->client->answers;
                $this->answer = $this->file->answers()->first();
            } elseif ($this->file->is_resource) {
                $this->assign_type = 'resource';
            }
        }

        public function mount(File $file)
        {
            $this->file = $file;
        }

        /**
         * todo: add validation
         *
         * @return string[]
         */
        public function rules()
        {
            return [
                'assign_type' => 'required',
                'task'        => 'required_if:assign_type,task',
                'answer'      => 'required_if:assign_type,answer',
                'src'         => 'required',
                'caption'     => 'max:1000',
            ];
        }

        protected $messages = [
            'src.required' => 'The File name cannot be empty.',
            'caption.max'  => 'The description cannot be more than 1,000 characters.',
        ];

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setClient($id)
        {
            $this->client = Client::with(['projects', 'answers'])->find($id);
            $this->projects = $this->client->projects;
            $this->answers = $this->client->answers;
        }

        /**
         * Set project in project select drop down.
         *
         * @param $id
         */
        public function setProject($id)
        {
            $this->project = Project::with(['phases'])->find($id);
        }

        /**
         * Set phase in phase select drop down.
         *
         * @param $id
         */
        public function setPhase($id)
        {
            $this->phase = Phase::with('tasks')->find($id);
            $this->tasks = $this->phase->tasks;
        }

        /**
         * Set Task in Task drop down.
         *
         * @param $id
         */
        public function setTask($id)
        {
            $this->task = Task::find($id);
        }

        /**
         * Set Task in Task drop down.
         *
         * @param $id
         */
        public function setAnswer($id)
        {
            $this->answer = Answer::find($id);
        }

        public function saveFile()
        {
            $this->validate();

            DB::beginTransaction();

            $this->file->tasks()->detach();
            $this->file->answers()->detach();

            $this->file->update([
                'src'           => $this->src,
                'caption'       => $this->caption,
                'is_resource'   => $this->assign_type == 'resource' ? true : false,
            ]);

            switch ($this->assign_type) {
                case('task'):
                    $this->file->tasks()->attach($this->task->id);
                    break;
                case('answer'):
                    $this->file->answers()->attach($this->answer->id);
                    break;
            }

            DB::commit();

            $this->emit('fileUpdated');
            $this->closeSlideout();

            flash('Files Updated')->success()->livewire($this);
        }

        public function render()
        {
            return view('livewire.admin.files.edit');
        }
    }
