@extends('settings.show')
@section('settings-content')
    <div>
        <!-- Description list with inline editing -->
        <div class="mt-10">
            <div class="space-y-1 mb-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Password
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Reset your password.
                </p>
            </div>
            @livewire('settings.password')
        </div>
    </div>
@endsection

