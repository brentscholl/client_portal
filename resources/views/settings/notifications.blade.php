@extends('settings.show')
@section('settings-content')
    <div>
        <!-- Description list with inline editing -->
        <div class="mt-10 divide-y divide-gray-200">
            <div class="space-y-1 mb-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Notifications
                </h3>
            </div>
            @livewire('settings.notifications')
        </div>
    </div>
@endsection
