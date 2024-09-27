<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\Client;
    use App\Models\Question;
    use App\Models\Service;
    use App\Models\Team;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 20;

        public $sortField = 'created_at';

        public $sortAsc = false;

        public $search = '';

        public $clients;

        public $selectedClient;

        public $services;

        public $selectedService;

        public $teams;

        public $selectedTeam;

        public $listeners = ['questionUpdated' => '$refresh', 'questionCreated' => '$refresh', 'questionDeleted' => '$refresh'];

        public function sortBy($field) {
            if ( $this->sortField === $field ) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortAsc = true;
            }

            $this->sortField = $field;
        }

        public function mount() {
            $this->clients = Client::all('title', 'id');
            $this->services = Service::all('title', 'id');
            $this->teams = Team::all('title', 'id');
        }

        public function destroy($id) {
            $question = Question::find($id);
            $question->delete();
            flash('Question Deleted')->success();
            return redirect()->route('admin.questions.index');
        }

        public function render() {
            $questions = Question::query();

            if($this->search !== ''){
                $questions = $questions->where('body', 'like', '%' . $this->search . '%');
            }

            if ( $this->selectedClient ) {
                $questions = $questions->where('client_id', $this->selectedClient);
            }

            if ( $this->selectedService ) {
                $questions = $questions->whereHas('services', function($q){
                    $q->where('questionable_id', $this->selectedService)->where('questionable_type', 'App\Models\Service');
                });
            }

            if ( $this->selectedTeam ) {
                $questions = $questions->whereHas('teams', function($q) {
                    $q->where('team_id', $this->selectedTeam)->where('teamable_type', 'App\Models\Question');
                });
            }

            $questions = $questions->autoloadIndex()->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

            return view('livewire.admin.questions.show-all', [
                'questions' => $questions->paginate($this->perPage),
            ]);
        }
    }
