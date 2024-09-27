@props([
    'position' => 'top',
])
<div x-cloak
    @switch($position)
        @case('left')
            x-show.transition.opacity="tooltip"
        @break
        @case('top')
            x-show.transition.opacity="tooltip"
        @break
        @default
            x-show.transition.opacity="tooltip"
        @break

    @endswitch
>
    <div class="tooltip__{{ $position }}">
        <div class="tooltip__popper">
            {{ $slot }}
        </div>
        <svg class="tooltip__arrow" width="8" height="8">
            <rect x="12" y="-10" width="8" height="8" transform="rotate(45)" />
        </svg>
    </div>
</div>
