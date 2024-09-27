<div x-data="{ tooltip: false }"
    x-on:mouseover="tooltip = true"
    x-on:mouseleave="tooltip = false"
    class="tooltip"
    >
    {{ $slot }}
</div>
