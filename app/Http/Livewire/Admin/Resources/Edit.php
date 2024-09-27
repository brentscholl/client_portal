<?php

namespace App\Http\Livewire\Admin\Resources;

use App\Models\Action;
use App\Models\File;
use App\Models\Resource;
use App\Traits\Resources\Resourceable;
use App\Traits\Slideable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use Resourceable, Slideable, WithFileUploads;

    public function mount(Resource $resource) {
        $this->resource = $resource;
    }

    public function load() {
        $this->type = $this->resource->type;
        $this->label = $this->resource->label;
        $this->tagline = $this->resource->tagline;
        $this->value = $this->resource->value;

        if($this->resource->type == 'file' && $this->resource->files->count() > 0){
            $this->uploaded_new_file = false;
            $this->selected_file = $this->resource->files->first();
        }

        if($this->type == 'file'){
            $this->assignable_files = File::whereHas('resources')->get();
        }
    }

    public function rules() {
        return [
            'type' => ['required'],
            'label' => ['required'],
            'assigned_file' => ['required_if:type,file'],
        ];
    }
    protected $messages = [
        'assigned_file.required_if' => 'You must include a file if Resource type is set as File.',
    ];

    /**
     * Update the resource
     */
    public function saveResource() {
        $this->assigned_file = $this->selected_file ? $this->selected_file : $this->uploaded_file;

        $this->validate();

        DB::beginTransaction();

        // Store files
        if($this->type == 'file' && $this->uploaded_file){
            // Store file
            $filename = $this->uploaded_file->store(
                'general',
                'files'
            );

            // Add record to DB
            $newFile = File::create([
                'user_id' => auth()->user()->id,
                'src' => $this->uploaded_file->getClientOriginalName(),
                'file_name' => $filename,
                'extension' => $this->uploaded_file->getClientOriginalExtension(),
                'file_size' => $this->uploaded_file->getSize(),
                'mime_type' => $this->uploaded_file->getMimeType(),
            ]);
            $this->uploaded_file = $newFile;
            $this->uploaded_new_file = true;
        }

        // Update task
        $this->resource->update([
            'type' => $this->type,
            'label' => $this->label,
            'tagline' => $this->tagline,
            'value' => $this->value,
        ]);

        if(isset($newFile)) {
            $this->resource->files()->attach($newFile);
        }

        $start_date = Carbon::now()->subHour();
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

        DB::commit();

        $this->closeSlideout();
        $this->emit('resourceUpdated');
        flash('Resource Updated!')->success()->livewire($this);
        $this->mount($this->resource);
    }

    public function render()
    {
        return view('livewire.admin.resources.edit');
    }
}
