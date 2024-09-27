<?php

    namespace App\Http\Livewire\Client\Projects;

    use App\Models\Assignee;
    use Livewire\Component;

    class ProjectCard extends Component
    {
        public $project;
        public $assignees;

        public function mount()
        {
            $this->assignees = Assignee::where(function ($w) {
                // Get Marketing Advisors attached to project
                $w->where('assigneeable_type', 'App\Models\Project')
                    ->where('assigneeable_id', $this->project->id);
            })->orWhere(function ($o) {
                // Get Marketing Advisors attached to project's client
                $o->where('assigneeable_type', 'App\Models\Client')
                    ->where('assigneeable_id', $this->project->client_id);
            })->orWhere(function ($o) {
                // Get Assignees attached to phase
                $o->where('assigneeable_type', 'App\Models\phase')
                    ->whereIn('assigneeable_id', $this->project->phases->pluck('id')->toArray());
            })->with('user')->get()->unique('user_id');
        }

        public function render()
        {
            return view('livewire.client.projects.project-card');
        }
    }
