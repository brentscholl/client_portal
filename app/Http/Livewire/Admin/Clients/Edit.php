<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Action;
    use App\Models\Client;
    use App\Traits\Clients\Clientable;
    use App\Traits\RealTimeValidation;
    use App\Traits\Slideable;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Component;
    use Livewire\WithFileUploads;

    class Edit extends Component
    {
        use Clientable, WithFileUploads, Slideable;

        public function mount() {
        }

        public function load() {
            // Set values
            $this->title = $this->client->title;
            $this->website_url = $this->client->website_url;
            $this->monthly_budget = $this->client->monthly_budget;
            $this->annual_budget = $this->client->annual_budget;
        }

        public function rules() {
            return [
                'title'        => ['required'],
                'website_url'    => ['string'],
                'monthly_budget' => ['numeric'],
                'annual_budget'  => ['numeric'],
                'newAvatar'      => [],
            ];
        }

        /**
         * Update the client
         * todo: remove old avatar
         * @return \Illuminate\Http\RedirectResponse
         */
        public function saveClient() {
            $this->validate();

            // Check what is being updated for action
            $values = [
                'Company Name' => $this->client->title !== $this->title,
                'Website URL' => $this->client->website_url !== $this->website_url,
                'Monthly Budget' => $this->client->monthly_budget !== $this->monthly_budget,
                'Annual Budget' => $this->client->annual_budget !== $this->annual_budget,
            ];

            $value_string = formatListForSentence($values);

            DB::beginTransaction();

            $this->client->update([
                'title'        => $this->title,
                'website_url'    => $this->website_url,
                'monthly_budget' => $this->monthly_budget,
                'annual_budget'  => $this->annual_budget,
            ]);
            $this->client->save(); // call save to reindex Algolia search

            // Save Avatar
            if ( $this->newAvatar ) {
                $filename = $this->newAvatar->store('/', 'avatars');

                $this->client->avatar = $filename;
                $this->client->save();
            }

            // Save action
            $start_date = Carbon::now()->subHour();
            $action = Action::where('type', 'model_updated')
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', Carbon::now())
                ->where('value', $value_string)
                ->where('actionable_type', 'App\Models\Client')
                ->where('actionable_id', $this->client->id)
                ->firstOrNew();

            $action->fill([
                'client_id' => $this->client->id,
                'user_id' => auth()->user()->id,
                'type' => 'model_updated',
                'value' => $value_string,
                'actionable_type' => get_class($this->client),
                'actionable_id' => $this->client->id,
            ]);

            $action->save();

            $this->emit('actionCreated');

            DB::commit();
            $this->closeSlideout();
            $this->emit('clientUpdated');
            flash('Client Updated!')->success();
            $this->mount();
        }

        public function render() {
            return view('livewire.admin.clients.edit');
        }
    }
