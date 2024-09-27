<?php

namespace App\Http\Livewire\Admin\Services;

use App\Models\Service;
use App\Traits\HasTabs;
use Livewire\Component;

class Show extends Component
{
    use HasTabs;

    public $service;

    protected $queryString = ['tab' => ['except' => 'details']];

    protected $listeners = ['serviceUpdated' => '$refresh', 'packageCreated' => '$refresh', 'packageDeleted' => '$refresh', 'packageUpdated' => '$refresh'];

    public function mount(Service $service) {
        $this->service = $service;
    }
    public function render()
    {
        return view('livewire.admin.services.show');
    }
}
