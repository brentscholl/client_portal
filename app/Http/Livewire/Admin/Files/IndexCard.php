<?php

namespace App\Http\Livewire\Admin\Files;

use App\Models\Answer;
use App\Models\File;
use App\Models\Question;
use App\Traits\Collapsible;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCard extends Component
{
    use Collapsible, WithPagination;
    public $model;
    public $setClient;
    public $client_id;
    public $allowUpload = true;
    public $perPage = 10;
    public $listeners = ['fileUpdated' => '$refresh', 'fileUploaded' => '$refresh', 'fileDeleted' => '$refresh'];

    public function mount() {

    }

    /**
     * Increase Pagination
     */
    public function loadMore() {
        $this->perPage = $this->perPage + 5;
    }

    public function render() {
        $files = File::query();

        switch(get_class($this->model)){
            case('App\Models\Client'):
                $files->whereHas('answers', function ($s) { // Get files for client's answers
                    $s->whereIn('fileable_id', $this->model->answers->pluck('id')->toArray());
                })->orWhereHas('tasks', function ($s) { // Get files for client's tasks
                    $s->whereIn('fileable_id', $this->model->tasks->pluck('id')->toArray());
                });
                break;
            case('App\Models\Project'):
                $files->whereHas('tasks', function ($t) {
                    $t->whereIn('fileable_id', $this->model->tasks->pluck('id')->toArray());
                })->orWhere(function ($a) { // Get all files attached to client's answers
                    // Get questions that belong to project
                    $question_ids = Question::whereHas('projects', function ($p) { // Get all questions attached to project
                        $p->where('questionable_id', $this->model->id);
                    })->orWhereHas('services', function ($s) { // Get all questions attached to project's attached service
                        $s->where('questionable_id', $this->model->service->id);
                    })->orWhereHas('packages', function ($p) { // Get all questions attached to project's attached packages
                        $p->whereIn('questionable_id', $this->model->packages->pluck('id')->toArray());
                    })->select('id')->get()->pluck('id')->toArray();
                    // Get answers that belong to project's questions
                    $answer_ids = Answer::where('client_id', $this->model->client_id)->whereIn('question_id', $question_ids)->select('id')->get()->pluck('id')->toArray();
                    $a->whereHas('answers', function ($a) use ($answer_ids) {
                        $a->whereIn('fileable_id', $answer_ids);
                    });
                });
                break;
            case('App\Models\Phase'):
                $files->orWhere(function ($t) { // Get all filess attached to client's tasks
                    $t->whereIn('fileable_id', $this->model->tasks->pluck('id')->toArray())->where('fileable_type', 'App\Models\Task');
                })->orWhere(function ($a) { // Get all files attached to client's answers
                    $a->whereIn('fileable_id', $this->model->answers->pluck('id')->toArray())->where('fileable_type', 'App\Models\Answer');
                })->paginate($this->perPage);
                break;
            case('App\Models\Task'):
            case('App\Models\Answer'):
                $files = $this->model->files();
                break;
        }
        $files = $files->with([
            'tasks',
            'answers',
            'user'
        ])->paginate($this->perPage);
        return view('livewire.admin.files.index-card', ['files' => $files]);
    }
}
