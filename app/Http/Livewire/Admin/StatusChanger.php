<?php

    namespace App\Http\Livewire\Admin;

    use App\Models\Action;
    use Illuminate\Support\Carbon;
    use Livewire\Component;

    class StatusChanger extends Component
    {
        public $model;

        public $class = '';

        public $large = false;

        public function mount($model) {
            $this->model = $model;
        }

        /**
         * Change status of a model
         * ie. pending, in-progress, on-hold, completed, canceled
         * @param $status
         */
        public function updateStatus($status) {
            // Handle completed at
            $completed_at = null;
            if ( $status == 'completed' ) {
                $completed_at = Carbon::now();
            }

            // Update Model
            $this->model->update([
                'status'       => $status,
                'completed_at' => $completed_at,
            ]);

            // Emit event based on model type.
            switch ( get_class($this->model) ) {
                case('App\Models\Task'):
                    $this->emit('taskUpdated');
                    break;
                case('App\Models\Project'):
                    $this->emit('projectUpdated');
                    break;
                case('App\Models\Phase'):
                    $this->emit('phaseUpdated');
                    break;
            }

            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'status_update')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('actionable_type', get_class($this->model))
                ->where('actionable_id', $this->model->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->model->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'status_update',
                'value' => $status,
                'actionable_type' => get_class($this->model),
                'actionable_id' => $this->model->id,
            ]);

            $action->save();

            $this->emit('actionCreated');

            $this->mount($this->model);
        }

        public function render() {
            return view('livewire.admin.status-changer');
        }
    }
