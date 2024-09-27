<?php

namespace App\Http\Livewire\Admin\Resources;

use App\Models\Action;
use App\Traits\HasMenu;
use Carbon\Carbon;
use Livewire\Component;

class ResourceCard extends Component
{
    use HasMenu;

    public $resource;

    public $value;

    public $model;

    public $is_editing = false;

    public function mount() {
        if($this->resource->type == 'date'){
            $this->value = Carbon::parse($this->resource->value)->format('M D, Y');
        }else{
            $this->value = $this->resource->value;
        }
    }

    public function updateValue() {
        $this->resource->update([
            'value' => $this->value,
        ]);

        $start_date = \Illuminate\Support\Carbon::now()->subHour();
        $action = Action::where('type', 'resource_updated')
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', Carbon::now())
            ->where('actionable_type', get_class($this->model))
            ->where('actionable_id', $this->model->id)
            ->firstOrNew();

        $action->fill([
            'client_id' => $this->model->client_id,
            'user_id' => auth()->user()->id,
            'type' => 'resource_updated',
            'actionable_type' => get_class($this->model),
            'actionable_id' => $this->model->id,
        ]);

        $action->save();

        $this->emit('actionCreated');

        $this->is_editing = false;
        $this->emit('resourceUpdated');
        flash('Resource Updated')->success()->livewire($this);
    }

    /**
     * Delete Resource
     */
    public function destroy() {
        // Delete Task
        $this->resource->delete();
        $this->emit('resourceDeleted');
        flash('Resource Deleted')->success()->livewire($this);
    }

    public function render()
    {
        return view('livewire.admin.resources.resource-card');
    }
}
