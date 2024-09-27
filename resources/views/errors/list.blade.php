@if($errors->all())
    <div class="bg-red-100 py-2 pl-3 pr-8 rounded-md relative">
        <ul class="text-xs list-disc">
            @foreach($errors->all() as $message)
                <li class="text-red-500">{{ $message }}</li>
            @endforeach
        </ul>
        <div class="text-red-500 absolute top-2 right-2">
            <x-svg.error class="h-4 w-4"/>
        </div>
    </div>
@endif
