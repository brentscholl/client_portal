@props([
    'label' => '',
    'description' => null,
    'first' => false,
    'last' => false
])
<div class="@if($first) rounded-tl rounded-tr @elseif($last) rounded-bl rounded-br @endif flex bg-gray-200 w-full p-4 z-10">
    <div class="h-4 w-4 mt-0.5 bg-gray-300 rounded-full"></div>
    <div class="ml-3 flex flex-col w-full flex-grow">
        <div class="block h-4 w-24 rounded bg-gray-300"></div>
        <div class="block h-4 w-80 mt-2 rounded bg-gray-300"></div>
    </div>
</div>
