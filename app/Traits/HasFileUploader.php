<?php

    namespace App\Traits;

    use App\Models\Action;
    use App\Models\File;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Livewire\WithFileUploads;

    trait HasFileUploader
    {
        use  WithFileUploads;

        public $files = [];

        public function addNewFile($model, $is_resource = false) {
            // Store files
            $i = 0;
            if($this->files){
                foreach($this->files as $file){
                    // Store file
                    $filename = $file->storeAs(
                        $this->setClient ? $this->client->id : $this->client_id,
                        bin2hex(random_bytes(3)) . '__' . $file->getClientOriginalName(),
                        'files'
                    );

                    // Add record to DB
                    $newFile = File::create([
                        'user_id' => auth()->user()->id,
                        'src' => $file->getClientOriginalName(),
                        'file_name' => $filename,
                        'extension' => $file->getClientOriginalExtension(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'is_resource' => $is_resource,
                    ]);
                    if($model){
                        $model->files()->attach($newFile);
                    }
                    $i++;
                }
            }

            if($model) {
                $action = Action::create([
                    'client_id'       => $this->setClient ? $this->client->id : $this->client_id,
                    'user_id'         => auth()->user()->id,
                    'type'            => 'file_upload',
                    'value'           => $i,
                    'actionable_type' => get_class($model),
                    'actionable_id'   => $model->id,
                ]);

                $this->emit('actionCreated');
            }
        }

        public function removeFile($id) {
            $file = File::find($id);

            if ( $file ) {
                Storage::disk('files')->delete($file->file_name);
                $file->delete();
            }else{
                flash('Unable to delete File. File not found.')->error();
            }

            $this->emit('fileDeleted');
            $this->mount();
            flash('File Deleted')->success();
        }
    }
