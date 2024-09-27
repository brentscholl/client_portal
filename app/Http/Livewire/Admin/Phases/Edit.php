<?php

    namespace App\Http\Livewire\Admin\Phases;

    use App\Models\Action;
    use App\Models\Phase;
    use App\Traits\Phases\Phaseable;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Edit extends Component
    {
        use Phaseable, Slideable;

        public function mount(Phase $phase) {
            $this->phase = $phase;
            $this->client = $phase->client;
            $this->title = $phase->title;
            $this->description = $phase->description;
            $this->due_date = $phase->due_date ? $phase->due_date->format("Y-m-d") : null;

            $this->attach_url = $phase->urls->count() > 0 ? 1 : 0;

            $urli = 0; // url count
            foreach ( $phase->urls as $url ) {
                $this->urls[$urli] = $url->url;
                $this->url_labels[$urli] = $url->label;
                $urli++;
            }
        }

        public function load() {

        }

        public function rules() {
            return [
                'title'    => ['required', 'string'],
                'due_date' => ['sometimes'],
            ];
        }

        /**
         * Update the phase
         */
        public function savePhase() {
            $this->validate();

            // Check what is being updated for action
            $values = [
                'Title' => $this->phase->title !== $this->title,
                'Description' => $this->phase->description !== $this->description,
                'Due Date' => Carbon::parse($this->phase->due_date)->format('Y-M-D') !== Carbon::parse($this->due_date)->format('Y-M-D'),
            ];

            $value_string = formatListForSentence($values);

            DB::beginTransaction();

            // Update phase
            $this->phase->update([
                'title'       => $this->title,
                'description' => $this->description,
                'due_date'    => $this->due_date,
            ]);

            // Delete old URLs
            foreach ( $this->phase->urls as $url ) {
                $url->delete();
            }

            // Add new URLs
            $this->saveUrls($this->phase);

            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'model_updated')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('value', $value_string)
                ->where('actionable_type', 'App\Models\Phase')
                ->where('actionable_id', $this->phase->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->phase->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'model_updated',
                'value' => $value_string,
                'actionable_type' => get_class($this->phase),
                'actionable_id' => $this->phase->id,
            ]);

            $action->save();


            $this->emit('actionCreated');

            DB::commit();

            $this->closeSlideout();
            $this->emit('phaseUpdated');
            flash('Phase Updated!')->success()->livewire($this);
            $this->mount($this->phase);
        }

        public function render() {
            return view('livewire.admin.phases.edit');
        }
    }
