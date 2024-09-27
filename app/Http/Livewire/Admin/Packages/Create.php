<?php

    namespace App\Http\Livewire\Admin\Packages;

    use App\Traits\Packages\Packageable;
    use App\Traits\Slideable;
    use App\Models\Package;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Packageable, Slideable;

        public function mount() {

        }

        public function load() {

        }

        public function rules() {
            return [
                'title'   => ['required', 'string'],
                'service' => ['required'],
            ];
        }

        /**
         * Create the new project
         */
        public function createPackage() {
            $this->validate();

            DB::beginTransaction();

            // Create project
            $project = Package::create([
                'service_id'  => $this->service->id,
                'title'       => $this->title,
                'description' => $this->description,
            ]);

            DB::commit();

            $this->closeSlideout();
            $this->emit('packageCreated');
            flash('Package Created!')->success()->livewire($this);
            $this->reset([
                'title',
                'description',
            ]);
        }

        public function render() {
            return view('livewire.admin.packages.create');
        }
    }
