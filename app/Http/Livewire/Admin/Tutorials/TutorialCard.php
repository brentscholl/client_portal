<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Traits\HasMenu;
    use Livewire\Component;

    class TutorialCard extends Component
    {
        use HasMenu;

        public $tutorial;

        public $setService = true;

        public $setClient = true;

        public $setPackage = true;

        public $embed_id;

        public $play = false;

        public $client_id;

        protected $listeners = ['tutorialUpdated' => '$refresh'];

        public function mount() {
            $this->embed_id = str_replace('/share/', '', parse_url($this->tutorial->video_url)['path']);
        }

        public function destroy() {
            // Delete tutorial
            $this->tutorial->delete();
            $this->emit('tutorialDeleted');
            flash('Tutorial Deleted')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.tutorials.tutorial-card');
        }
    }
