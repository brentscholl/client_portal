<?php

    namespace App\Http\Livewire\Settings;

    use Livewire\Component;

    class Notifications extends Component
    {
        public $is_subscribed_to_news;

        public function mount() {
            $user = auth()->user();
            $this->is_subscribed_to_news = $user->settings()->get('is_subscribed_to_news');
        }

        public function setState($setting) {
            $currentState = auth()->user()->settings()->get($setting);
            auth()->user()->settings()->update($setting, ! $currentState);
            $this->mount();
        }

        public function render() {
            return view('livewire.settings.notifications');
        }
    }
