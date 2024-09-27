<div class="{{ $attributes->get('class') }}" x-data="{ open: false }" @close-slideout.window="open = false" @keydown.window.escape="open = false;">
    {{ $slot }}
</div>
