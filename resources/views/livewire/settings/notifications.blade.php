<div>
    @section('alert')
        <x-alert.saved message="Notifications updated!"></x-alert.saved>
    @endsection
    <x-input.dl>
        <x-input.dl.radio
            wire:model="is_subscribed_to_news"
            label="Send me emails about news from Stealth"
            toggled="{{ auth()->user()->settings()->get('is_subscribed_to_news') ? 'true' : 'false' }}"
            class=""
        />
    </x-input.dl>
</div>

