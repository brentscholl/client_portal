<?php

    namespace App\Http\Livewire\Admin\Resources;

    use App\Models\Resource;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $model;

        public $filter = 'all';

        public $perPage = 10;

        public $listeners = ['resourceUpdated' => '$refresh', 'resourceCreated' => '$refresh', 'resourceDeleted' => '$refresh'];

        public function render() {
            $resources = Resource::query();

            if ( $this->filter == 'input' ) {
                $resources = $resources->where('type', '!=', 'file');
            }

            if ( $this->filter == 'files' ) {
                $resources = $resources->where('type', 'file');
            }

            $resources = $resources->where('resourceable_type', get_class($this->model))
                ->where('resourceable_id', $this->model->id)
                ->with([
                    'files'
                ])->paginate($this->perPage);

            return view('livewire.admin.resources.index-card', ['resources' => $resources]);
        }
    }
