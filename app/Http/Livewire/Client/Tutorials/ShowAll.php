<?php

    namespace App\Http\Livewire\Client\Tutorials;

    use App\Models\Tutorial;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 10;

        /**
         * Increase Pagination
         */
        public function loadMore()
        {
            $this->perPage = $this->perPage + 5;
        }

        public function render()
        {
            // Where is this component
            $tutorials = Tutorial::query();

            $tutorials = $tutorials->whereHas('clients', function ($c) { // Get tutorials for client
                $c->where('tutorialable_id', auth()->user()->client_id);
            })->orWhereHas('services', function ($s) { // Get tutorials for client's attached services
                $s->whereIn('tutorialable_id', auth()->user()->client->services->pluck('id')->toArray());
            })->orWhereHas('packages', function ($p
            ) { // Get tutorials for client's attached services' attached packages
                $package_ids = [];
                foreach (auth()->user()->client->projects as $project) {
                    if ($project->packages->count() > 0) {
                        array_push($package_ids, $project->packages->pluck('id')->toArray());
                    }
                }
                $p->whereIn('tutorialable_id', $package_ids);
            });

            $tutorials = $tutorials
                ->paginate($this->perPage);

            return view('livewire.client.tutorials.show-all', ['tutorials' => $tutorials]);
        }
    }
