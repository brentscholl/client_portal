<?php

    namespace App\Http\Livewire\Client\Questions;

    use App\Models\Question;
    use Illuminate\Database\Eloquent\Builder;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $filter = 'all';

        public $perPage = 20;

        public $listeners = [
            'questionUpdated' => '$refresh',
            'questionCreated' => '$refresh',
            'questionDeleted' => '$refresh',
            'answerCreated'   => '$refresh',
            'answerUpdated'   => '$refresh',
        ];

        public function mount()
        {

        }

        /**
         * Change the filter on the index card
         *
         * @param $filter
         */
        public function filter($filter)
        {
            $this->filter = $filter;
            $this->mount();
        }

        /**
         * Increase Pagination
         */
        public function loadMore()
        {
            $this->perPage = $this->perPage + 20;
        }

        public function render()
        {
            $questions = Question::query();

            $questions = $questions->where(function (Builder $query) {
                return $query->where('is_onboarding', 1)
                    ->orWhereRelation('clients', 'questionable_id', auth()->user()->client_id)
                    ->orWhereHas('projects', function ($p) { // Get all questions attached to client's projects
                        $p->whereIn('questionable_id', auth()->user()->client->projects->pluck('id')->toArray());
                    })->orWhereHas('services', function ($s
                    ) { // Get all questions attached to client's attached services
                        $s->whereIn('questionable_id', auth()->user()->client->services->pluck('id')->toArray());
                    })->orWhereHas('packages', function ($p
                    ) { // Get all questions attached to client's attached packages
                        $package_ids = [];
                        foreach (auth()->user()->client->projects as $project) {
                            if ($project->packages->count() > 0) {
                                array_push($package_ids, $project->packages->pluck('id')->toArray());
                            }
                        }
                        $p->whereIn('questionable_id', $package_ids);
                    });
            });

            // Filter ----
            if ($this->filter != 'all') {
                switch ($this->filter) {
                    case('answered'): // Get answered questions
                        $questions = $questions->whereRelation('answers', 'client_id', auth()->user()->client_id);
                        break;
                    case('unanswered'): // Get unanswered questions
                        $questions = $questions->whereDoesntHave('answers', function ($a) {
                            $a->where('client_id', auth()->user()->client_id);
                        });
                        break;
                }
            }

            // Eager load -----
            $questions = $questions->with([
                'services:id,slug,title',
                'packages',
                'packages.service:id,title,slug',
                'projects:id,client_id,service_id,title',
                'projects.client:id,title',
                'clients:id,title',
                'teams:id,title',
            ])->with([
                'answers' => function ($w) {
                    $w->with(['files', 'user:id,first_name,last_name,deleted_at']);
                    if (auth()->user()->client) {
                        $w->where('client_id', auth()->user()->client_id);
                    }
                },
            ])->orderBy('is_onboarding', 'desc')->paginate($this->perPage);

            return view('livewire.client.questions.show-all', ['questions' => $questions]);
        }
    }
