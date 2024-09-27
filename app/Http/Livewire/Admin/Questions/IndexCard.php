<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\Question;
    use App\Traits\Collapsible;
    use Illuminate\Database\Eloquent\Builder;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $client;

        public $setClient = true;

        public $project;

        public $setProject = true;

        public $service;

        public $setService = true;

        public $package;

        public $setPackage = true;

        public $team;

        public $filter = 'all';

        public $assign_type = 'client';

        public $perPage = 10;

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
            $this->perPage = $this->perPage + 10;
        }

        public function render()
        {
            // Where is this component?
            $questions = Question::query();


            if ($this->project) { // For single Project page

                $questions = $questions->where(function (Builder $query) {
                    return $query->where('is_onboarding', 1)
                        ->orWhereRelation('projects', 'questionable_id', $this->project->id)
                        ->orWhereRelation('services', 'questionable_id', $this->project->service->id)
                        ->orWhereHas('packages', function ($p
                        ) { // Get all questions attached to project's attached packages
                            $p->whereIn('questionable_id', $this->project->packages->pluck('id')->toArray());
                        });
                });
            }elseif ($this->client) { // For single Client page

                $questions = $questions->where(function (Builder $query) {
                    return $query->where('is_onboarding', 1)
                        ->orWhereRelation('clients', 'questionable_id', $this->client->id)
                        ->orWhereHas('projects', function ($p) { // Get all questions attached to client's projects
                            $p->whereIn('questionable_id', $this->client->projects->pluck('id')->toArray());
                        })->orWhereHas('services', function ($s) { // Get all questions attached to client's attached services
                            $s->whereIn('questionable_id', $this->client->services->pluck('id')->toArray());
                        })->orWhereHas('packages', function ($p) { // Get all questions attached to client's attached packages
                            $package_ids = [];
                            foreach ($this->client->projects as $project) {
                                if ($project->packages->count() > 0) {
                                    array_push($package_ids, $project->packages->pluck('id')->toArray());
                                }
                            }
                            $p->whereIn('questionable_id', $package_ids);
                        });
                });
            }

            if ($this->package) { // For single Package page
                $questions = $questions->where(function (Builder $query) {
                    return $query->where('is_onboarding', 1)
                        ->orWhereRelation('packages', 'questionable_id', $this->package->id);
                });
            } elseif ($this->service) { // For single Service page
                $questions = $questions->where(function (Builder $query) {
                    return $query->where('is_onboarding', 1)
                        ->orWhereRelation('services', 'questionable_id', $this->service->id);
                });
            }

            if ($this->team) { // For single Team page
                $questions = $questions->where(function (Builder $query) {
                    return $query->where('is_onboarding', 1)
                        ->orWhereRelation('teams', 'team_id', $this->team->id);
                });
            }

            // Filter ----
            if ($this->filter != 'all') {
                switch ($this->filter) {
                    case('answered'): // Get answered questions
                        $questions = $questions->whereRelation('answers', 'client_id', $this->client->id);
                        break;
                    case('unanswered'): // Get unanswered questions
                        $questions = $questions->whereDoesntHave('answers', function ($a) {
                            $a->where('client_id', $this->client->id);
                        });
                        break;
                    case('unique'): // Get unanswered questions
                        $questions = $questions->whereRelation('clients', 'questionable_id', $this->client->id);
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
                    if($this->client){
                        $w->where('client_id', $this->client->id);
                    }
                },
            ])->orderBy('is_onboarding', 'desc')->paginate($this->perPage);

            return view('livewire.admin.questions.index-card', ['questions' => $questions]);
        }
    }
