<?php

    namespace App\Http\Livewire\Admin\Packages;

    use App\Models\Package;
    use App\Models\Project;
    use App\Traits\HasMenu;
    use Livewire\Component;

    class PackageCard extends Component
    {
        use HasMenu;

        public $package;

        public $showService = true;

        protected $listeners = ['packageUpdated' => '$refresh'];

        public function mount(Package $package) {
            $this->package = $package;
        }

        /**
         * Delete the package
         */
        public function destroy() {
            // Delete the package
            $this->package->delete();
            $this->emit('packageDeleted');
            flash('Package Deleted')->success()->livewire($this);
        }

        public function render() {
            return view('livewire.admin.packages.package-card');
        }
    }
