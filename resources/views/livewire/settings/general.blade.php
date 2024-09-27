<div>
    @section('alert')
        <x-alert.saved></x-alert.saved>
    @endsection
    <x-input.dl>
        <x-input.dl.full-name
            label="Name"
            :required="true"
            class=""
        >
            {{ auth()->user()->full_name }}
        </x-input.dl.full-name>

        <x-input.dl.image
            wire:model="newAvatar"
            multiple
            label="Photo"
            class=""
        >
            {{ auth()->user()->avatarUrl() }}
        </x-input.dl.image>

        <x-input.dl.text
            wire:model.lazy="email"
            type="text"
            label="Email"
            :required="true"
            placeholder=""
            class=""
        >
            {{ $email }}
        </x-input.dl.text>

        <x-input.dl.text
            wire:model.lazy="phone"
            type="tel"
            label="Phone"
            :required="true"
            placeholder=""
            class=""
        >
            {{ $phone }}
        </x-input.dl.text>

        <x-input.dl.text
            wire:model.lazy="position"
            type="text"
            label="Position"
            placeholder=""
            class=""
        >
            {{$position }}
        </x-input.dl.text>
    </x-input.dl>
</div>
