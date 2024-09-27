@props([
    'compact' => false,
])
<fieldset class="{{ $compact ? 'space-y-2' : 'space-y-5' }}">
    {{ $slot }}
</fieldset>
