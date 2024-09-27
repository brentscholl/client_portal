<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Models\Tutorial;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $client;

        public $project;

        public $setClient = true;

        public $service;

        public $setService = true;

        public $package;

        public $setPackage = true;

        public $status = 'all';

        public $assign_type = 'client';

        public $perPage = 5;

        public $listeners = ['tutorialUpdated' => '$refresh', 'tutorialCreated' => '$refresh', 'tutorialDeleted' => '$refresh'];

        public function mount() {

        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 5;
        }

        public function render() {
            // Where is this component
            $tutorials = Tutorial::query();

            if ( $this->project ) { // For single Project page

                $tutorials = $tutorials->whereHas('services', function ($s) { // Get tutorials for project's service
                    $s->whereIn('tutorialable_id', $this->client->services->pluck('id')->toArray());
                })->orWhereHas('packages', function ($p) { // Get tutorials for project's attacjed packages
                    $p->whereIn('tutorialable_id', $this->project->packages->pluck('id')->toArray());
                });

            } elseif ( $this->client ) { // For single Client page

                $tutorials = $tutorials->whereHas('clients', function ($c) { // Get tutorials for client
                    $c->where('tutorialable_id', $this->client->id);
                })->orWhereHas('services', function ($s) { // Get tutorials for client's attached services
                    $s->whereIn('tutorialable_id', $this->client->services->pluck('id')->toArray());
                })->orWhereHas('packages', function ($p) { // Get tutorials for client's attached services' attached packages
                    $package_ids = [];
                    foreach ( $this->client->projects as $project ) {
                        if ( $project->packages->count() > 0 ) {
                            array_push($package_ids, $project->packages->pluck('id')->toArray());
                        }
                    }
                    $p->whereIn('tutorialable_id', $package_ids);
                });

            } elseif ( $this->package ) { // For single Package page
                $tutorials = $tutorials->whereHas('packages', function ($p) { // Get tutorials for package
                    $p->where('tutorialable_id', $this->package->id);
                });
            } elseif ( $this->service ) { // For single Service page
                $tutorials = $tutorials->whereHas('services', function ($s) { // Get tutorials for service
                    $s->where('tutorialable_id', $this->service->id);
                });
            }
            $tutorials = $tutorials
                ->with([
                    'services',
                    'packages',
                    'clients'
                ])
                ->paginate($this->perPage);
            return view('livewire.admin.tutorials.index-card', ['tutorials' => $tutorials]);
        }
    }
