@props([
'route',
'is',
])

<a href="{{route($route)}}"
    class="group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150
    {{Request::is($is) ? 'text-white sidebar-select-gradient' : 'text-primary-100 hover:text-white sidebar-select-gradient-hover'}}
        ">
    {{$slot}}
</a>
