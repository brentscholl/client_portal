@props([
    'route' => '',
    'current' => false
])
<li class="flex">
    <div class="flex items-center">
        <svg class="flex-shrink-0 w-6 h-full text-gray-200" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
        </svg>
        @if($current)
            <span class="ml-4 text-sm font-medium text-gray-900 flex space-x-2 items-center">{{ $slot }}</span>
        @else
            <a
                @if($attributes->get('tooltip'))
                tooltip="{{ $attributes->get('tooltip') }}"
                tooltip-p="bottom"
                @endif
                href="{{ $route }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-secondary-500 flex space-x-2 items-center">{{ $slot }}</a>
        @endif
    </div>
</li>
