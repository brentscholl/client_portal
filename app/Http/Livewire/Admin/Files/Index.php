<?php

    namespace App\Http\Livewire\Admin\Files;

    use App\Models\File;
    use App\Traits\HasFileUploader;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Index extends Component
    {
        use HasFileUploader;

        public $tab = 'all';

        public $view_mode = 'grid';

        public $selected_id;

        public $selected_file;

        public $is_adding_description = false;

        public $caption;

        protected $queryString = ['tab' => ['except' => 'all'], 'view_mode' => ['except' => 'grid']];

        public $listeners = ['fileUpdated' => '$refresh', 'fileUploaded' => 'fileUploaded', 'fileDeleted' => '$refresh'];

        /**
         * Sets before render
         */
        public function mount() {
            $files = File::query();
            switch ( $this->tab ) {
                case('images'):
                    $files = $files->getImages();
                    break;
                case('documents'):
                    $files = $files->getDocuments();
                    break;
                case('video'):
                    $files = $files->getVideos();
                    break;
                case('audio'):
                    $files = $files->getAudios();
                    break;
                case('resource'):
                    $files = $files->whereHas('resources')->orWhere(function ($q){
                        $q->whereDoesntHave('tasks')->whereDoesntHave('answers');
                    });
            }
            $files = $files
                ->with([
                    'resources',
                    'tasks',
                    'answers',
                ])
                ->orderBy('created_at', 'desc')
                ->get();
            $this->files = $files;


            if($this->selected_id){
                $this->selected_file = File::find($this->selected_id);
            }else{
                $this->selected_file = $this->files->first();
            }
            if ( $this->selected_file ) {
                $this->caption = $this->selected_file->caption;
            }
        }

        public function changeTab($tab) {
            $this->tab = $tab;
            $this->mount();
        }

        public function rules() {
            return [
                'caption' => 'max:1000',
            ];
        }

        protected $messages = [
            'caption.max' => 'The description cannot be more than 1,000 characters.',
        ];

        public function saveDescription() {
            $this->validate();

            DB::beginTransaction();
            $this->selected_file->update([
                'caption' => $this->caption,
            ]);
            DB::commit();
            $this->is_adding_description = false;
            $this->emit('fileUpdated');
            flash('File Updated!')->success()->livewire($this);
        }

        public function selectFile(File $file) {
            $this->selected_file = $file;
        }

        public function uploaded()
        {
            $this->selected_file = $this->files->first();
            $this->mount();
            //return redirect()->route('admin.files.index');
        }

        public function render() {
            return view('livewire.admin.files.index');
        }
    }
