@props([
    'index' => 0,
    'label' => null,
])
<fieldset x-data="window.Components.radioGroup({ initialCheckedIndex: {{ $index }} })" x-init="init()">
    @if($label)
        <label class="mb-1">
            {{ $label }}
        </label>
    @endif
    <div class="bg-white rounded-md -space-y-px">
        {{ $slot }}
    </div>
</fieldset>
