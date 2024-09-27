<!-- Static sidebar for desktop -->
<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-48">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto gradient">
            @if(auth()->user()->isAdmin())
                @include('layouts.admin.sidebar')
            @else
                @include('layouts.client.sidebar')
            @endif
        </div>
    </div>
</div>
