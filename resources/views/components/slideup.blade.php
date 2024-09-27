@props([
    'title' => '',
    'subtitle' => '',
    'cancelBtn' => 'Cancel',
    'saveBtn' => 'Save',
    'showButtons' => true,
])
<div>
    <div x-cloak x-show="open"
        class="z-50 fixed backdrop-blur-2 inset-0 overflow-hidden"
        x-transition:enter="transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="background: rgba(0,0,0,0.07);"
        @click="open = false"
    ></div>
    <div class="ml-24 fixed bottom-0 w-full max-w-4xl z-50 "
        style="
        left: 50%;
        transform:translateX(-50%);
        "
        x-cloak x-show="open"
    >
        <div class="px-6 pt-6 pb-2 rounded-t-xl shadow-2xl bg-white overflow-y-auto"
            style="
            box-shadow: rgb(27 33 58 / 40%) 0px 20px 60px -2px;
            max-height: 85vh;
            "
            x-cloak x-show="open"
            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
        >
            <div class="flex space-x-2 justify-between items-center">
                <h2 class="text-lg text-gray-900 font-bold">{{ $title }}</h2>
                @if($subtitle)
                    <p>{{ $subtitle }}</p>
                @endif
                <button type="button" @click="open = false"
                    class="flex justify-center items-center rounded-full h-6 w-6 text-gray-500 bg-gray-50 hover:bg-gray-100 hover:shadow">
                    <x-svg.x class="h-4 w-4"/>
                </button>
            </div>
            <hr class="mt-6">
            {{ $slot }}
            @if($showButtons)
                <div class="flex-shrink-0 px-4 py-4 flex justify-end space-x-2">
                    <x-button @click="open = false;" btn="cancel">
                        {!! $cancelBtn !!}
                    </x-button>
                    <x-button type="submit" :loader="true">
                        {!! $saveBtn !!}
                    </x-button>
                </div>
            @endif
        </div>
    </div>
</div>
