@props([
    'label' => "",
])
<div class="max-w-2xl mx-auto">
    <div class="relative">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-start">
              <span class="pr-2 bg-white text-sm text-gray-500">
                {!! $label !!}
              </span>
        </div>
    </div>
</div>
