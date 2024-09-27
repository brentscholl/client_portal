<?php

    namespace App\Http\Livewire\Admin\Packages;

    use App\Models\Package;
    use App\Traits\Packages\Packageable;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Packageable, Slideable;

        public function mount(Package $package) {
            $this->package = $package;
        }

        public function load() {
            $this->title = $this->package->title;
            $this->description = $this->package->description;
            $this->service = $this->package->service;
        }

        public function rules() {
            return [
                'title' => ['required', 'string'],
            ];
        }

        /**
         *
         */
        public function savePackage() {
            $this->validate();

            DB::beginTransaction();

            // Update project
            $this->package->update([
                'title'       => $this->title,
                'description' => $this->description,
            ]);

            DB::commit();

            $this->closeSlideout();
            $this->emit('packageUpdated');
            flash('Package Updated!')->success()->livewire($this);
            $this->mount($this->package);
        }

        public function render() {
            return view('livewire.admin.packages.edit');
        }
    }
