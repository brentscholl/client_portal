<?php

    namespace App\Http\Livewire\Admin\Resources;

    use App\Models\Action;
    use App\Models\File;
    use App\Models\Resource;
    use App\Traits\HasFileUploader;
    use App\Traits\Resources\Resourceable;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;
    use Livewire\WithFileUploads;

    class Create extends Component
    {
        use Resourceable, WithFileUploads, Slideable;

        public function load() {

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

        public function createResource() {
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
                        'is_resource' => true,
                    ]);
                    $this->uploaded_file = $newFile;
                    $this->uploaded_new_file = true;
            }

            $resource = Resource::create([
                'client_id'   => get_class($this->model) == 'App\Models\Client' ? $this->model->id : $this->model->client_id,
                'resourceable_type' => get_class($this->model),
                'resourceable_id' => $this->model->id,
                'type' => $this->type,
                'label' => $this->label,
                'tagline' => $this->tagline,
                'value' => $this->value,
            ]);

            if($this->uploaded_new_file) {
                $resource->files()->attach($this->uploaded_file);
            }else{
                $resource->files()->attach($this->selected_file);
            }

            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'resource_updated')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('actionable_type', get_class($this->model))
                ->where('actionable_id', $this->model->id)
                ->firstOrNew();

            $action->fill([
                'client_id'   => get_class($this->model) == 'App\Models\Client' ? $this->model->id : $this->model->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'resource_updated',
                'actionable_type' => get_class($this->model),
                'actionable_id' => $this->model->id,
            ]);

            $action->save();

            $this->emit('actionCreated');

            DB::commit();

            $this->reset([
                'type',
                'label',
                'tagline',
                'value',
                'selected_file',
                'uploaded_file',
                'assigned_file'
            ]);

            $this->emit('resourceCreated');
            $this->closeSlideout();

            flash('Resource Created')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.resources.create');
        }
    }
