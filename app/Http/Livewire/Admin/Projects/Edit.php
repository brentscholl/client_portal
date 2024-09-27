<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Service;
    use App\Traits\Projects\Projectable;
    use App\Traits\RealTimeValidation;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Projectable, Slideable;

        public function mount(Project $project) {
            $this->project = $project;
        }

        public function load() {

            if ( $this->project->packages ) {
                foreach ( $this->project->packages as $package ) {
                    array_push($this->package_ids, $package->id);
                }
            }
            $this->services = Service::all();

            // Set current values
            $this->client = $this->project->client;
            $this->setClient = false; // They should delete project if it's for the wrong client.
            $this->title = $this->project->title;
            $this->description = $this->project->description;
            $this->service = $this->project->service;
            $this->due_date = $this->project->due_date ? $this->project->due_date->format("Y-m-d") : null;

            // Set available selections
            if ( $this->service && $this->setPackage ) {
                $this->assignable_packages = Package::where('service_id', $this->service->id)->select('service_id', 'id', 'title')->get();
            }

            $this->attach_url = $this->project->urls->count() > 0 ? 1 : 0;

            $urli = 0; // url count
            foreach ( $this->project->urls as $url ) {
                $this->urls[$urli] = $url->url;
                $this->url_labels[$urli] = $url->label;
                $urli++;
            }
            $this->hidden = ! $this->project->visible;
        }

        public function rules() {
            return [
                'title'    => ['required', 'string'],
                'due_date' => ['sometimes'],
                'client'   => ['required'],
                'service'  => ['required'],
            ];
        }

        /**
         * Update the project
         */
        public function saveProject() {
            $this->validate();

            // Check what is being updated for action
            $values = [
                'Assigned Service' => $this->project->service_id !== $this->service->id,
                'Assigned Packages' => $this->project->packages->pluck('id')->toArray() !== $this->package_ids,
                'Title' => $this->project->title !== $this->title,
                'Description' => $this->project->description !== $this->description,
                'Due Date' => Carbon::parse($this->project->due_date)->format('Y-M-D') !== Carbon::parse($this->due_date)->format('Y-M-D'),
            ];

            $value_string = formatListForSentence($values);

            DB::beginTransaction();

            // Update project
            $this->project->update([
                'title'       => $this->title,
                'description' => $this->description,
                'service_id'  => $this->service->id,
                'due_date'    => $this->due_date,
                'visible'     => ! $this->hidden,
            ]);

            $this->project->save(); // call save to reindex Algolia search

            // update packages
            $this->project->packages()->detach();
            if ( $this->package_ids ) {
                foreach ( $this->package_ids as $id ) {
                    $this->project->packages()->attach($id);
                }
            }

            // Delete old URLs
            foreach ( $this->project->urls as $url ) {
                $url->delete();
            }

            // Add new URLs
            $this->saveUrls($this->project);

            // Save action
            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'model_updated')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('value', $value_string)
                ->where('actionable_type', 'App\Models\Project')
                ->where('actionable_id', $this->project->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->project->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'model_updated',
                'value' => $value_string,
                'actionable_type' => get_class($this->project),
                'actionable_id' => $this->project->id,
            ]);

            $action->save();

            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('projectUpdated');
            flash('Project Updated!')->success()->livewire($this);
            $this->mount($this->project);
        }

        public function render() {
            return view('livewire.admin.projects.edit');
        }
    }
