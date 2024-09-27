<?php

    namespace App\Http\Livewire\Admin\Packages;

    use App\Models\Package;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;


        public $service;

        public $showService = true;

        public $perPage = 5;

        public $listeners = ['packageUpdated' => '$refresh', 'packageCreated' => '$refresh', 'packageDeleted' => '$refresh'];

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 5;
        }

        public function render() {
            $packages = Package::query();
            $packages = $packages->where('service_id', $this->service->id);
            $packages = $packages->with([
                'questions:id',
                'tutorials:id',
            ])->paginate($this->perPage);
            return view('livewire.admin.packages.index-card', ['packages' =>  $packages]);
        }
    }
