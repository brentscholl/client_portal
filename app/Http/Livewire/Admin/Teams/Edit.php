<?php

    namespace App\Http\Livewire\Admin\Teams;

    use App\Traits\Slideable;
    use App\Traits\Teams\Teamable;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;
    use Livewire\Component;

    class Edit extends Component
    {
        use Teamable, Slideable;

        public function mount() {
        }

        public function load() {
            // Set current values
            $this->title = $this->team->title;
            $this->description = $this->team->description;
        }

        public function rules() {
            return [
                'title'       => ['required', 'string', 'min:2', 'max:100'],
                'description' => ['nullable', 'string', 'min:0', 'max:1000'],
            ];
        }

        /**
         * Update the team
         */
        public function saveTeam() {
            $this->validate();

            DB::beginTransaction();

            // Create teams
            $this->team->update([
                'title'       => $this->title,
                'slug'        => Str::of($this->title)->slug('-'),
                'description' => $this->description,
            ]);

            DB::commit();

            $this->closeSlideout();
            $this->emit('teamUpdated');
            flash('Team Updated!')->success()->livewire($this);
            $this->mount();
        }

        public function render() {
            return view('livewire.admin.teams.edit');
        }
    }
