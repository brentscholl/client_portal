<?php

    namespace App\Http\Livewire\Admin\Activities;

    use App\Models\Action;
    use App\Models\User;
    use App\Notifications\Mentioned;
    use App\Traits\Collapsible;
    use App\Traits\RealTimeValidation;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $model;

        public $showClientLabel = false;

        public $newComment;

        public $mentionableUsers;

        public $allowComments = true;

        public $status = 'all';

        public $filter_children = true;

        public $perPage = 5;

        public $listeners = [
            'actionUpdated'  => '$refresh',
            'actionCreated'  => '$refresh',
            'actionDeleted'  => '$refresh',
            'replyToComment' => 'replyToComment',
        ];

        public function mount()
        {
            $this->emit('postAdded');
            if ($this->model && $this->allowComments) {

                $this->mentionableUsers = User::where('client_id', $this->model->client_id)->orWhere('client_id', null)->orderBy('first_name', 'asc')->select([
                    'first_name',
                    'last_name',
                    'id',
                ])->get();
            }
        }

        public function rules()
        {
            return [
                'newComment' => 'required|max:6000',
            ];
        }

        protected $messages = [
            'newComment.require' => 'Please type a comment.',
            'newComment.max'     => 'Comments can only be up to 6000 characters long.',
        ];

        public function createComment()
        {
            $this->validate();

            DB::beginTransaction();

            // Create Task
            $comment = Action::create([
                'client_id'       => get_class($this->model) == 'App\Models\Client' ? $this->model->id : $this->model->client_id,
                'user_id'         => auth()->user()->id,
                'type'            => 'comment',
                'body'            => $this->newComment,
                'actionable_type' => get_class($this->model),
                'actionable_id'   => $this->model->id,
            ]);

            preg_match_all('/data-userid=\"(.*?)\"/', $this->newComment, $matches);
            $mentioned_user_ids = [];
            foreach ($matches[1] as $mentionId) {
                $user = User::find($mentionId);
                array_push($mentioned_user_ids, $user->id);
                $user->notify(new Mentioned(auth()->user(), $comment, $this->model));
            }

            $comment->update([
                'mention_ids' => json_encode($mentioned_user_ids),
            ]);

            DB::commit();
            $this->reset([
                'newComment',
            ]);

            $this->emit('actionCreated');
            $this->emit('commentCreated');
            $this->mount();
        }

        /**
         * Change filter
         *
         * @param $status
         */
        public function updateStatus($status)
        {
            $this->status = $status;
            $this->mount();
        }

        /**
         * Delete Action
         *
         * @param $id
         */
        public function destroyAction($id)
        {
            Action::find($id)->delete();
            $this->emit('actionDeleted');
        }

        /**
         * Increase Pagination
         */
        public function loadMore()
        {
            $this->perPage = $this->perPage + 5;
        }

        public function render()
        {

            $actions = Action::query();
            if ($this->model) {
                if ($this->filter_children) {
                    if (get_class($this->model) == 'App\Models\Client') { // Get All actions for client and children models
                        $actions->where('client_id', $this->model->id);
                    } elseif (get_class($this->model) == 'App\Models\Project') { // Get All actions for project and children models
                        $actions->whereHas('actionable', function ($c) { // Get actions for project
                            $c->where('actionable_id', $this->model->id)->where('actionable_type', 'App\Models\Project');
                        })->orWhereHas('actionable', function ($p) { // Get actions for project's phases
                            $p->whereIn('actionable_id', $this->model->phases->pluck('id')->toArray())->where('actionable_type', 'App\Models\Phase');
                        })->orWhereHas('actionable', function ($t) {// Get actions for project's attached tasks
                            $t->whereIn('actionable_id', $this->model->tasks->pluck('id')->toArray())->where('actionable_type', 'App\Models\Task');
                        });
                    } elseif (get_class($this->model) == 'App\Models\Phase') { // Get All actions for phase and children models
                        $actions->whereHas('actionable', function ($c) { // Get actions for phase
                            $c->where('actionable_id', $this->model->id)->where('actionable_type', 'App\Models\Phase');
                        })->orWhereHas('actionable', function ($t) {// Get actions for phase's attached tasks
                            $t->whereIn('actionable_id', $this->model->tasks->pluck('id')->toArray())->where('actionable_type', 'App\Models\Task');
                        });
                    } elseif (get_class($this->model) == 'App\Models\Task') { // Get All actions for task
                        $actions->whereHas('actionable', function ($c) { // Get actions for task
                            $c->where('actionable_id', $this->model->id)->where('actionable_type', 'App\Models\Task');
                        });
                    } elseif (get_class($this->model) == 'App\Models\Service') {
                        $actions->orWhere('actionable_type', 'App\Models\Package');
                    }
                } else {
                    $actions->where('actionable_type', get_class($this->model))
                        ->where('actionable_id', $this->model->id);
                }
            }

            // Filter -----
            if ($this->status != 'all') {
                if ($this->status == 'comments') {
                    $actions->where('type', 'comment');
                } else {
                    $actions->where('type', '!=', 'comment');
                }
            }
            $actions = $actions
                ->with([
                    'user',
                    'actionable',
                    'client:id,title',
                    'reactions'
                ])
                ->latest()->paginate($this->perPage);

            return view('livewire.admin.activities.index-card', ['actions' => $actions]);
        }
    }
