@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
        <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Users
            </h3>
            <div class="mt-3 sm:mt-0 sm:ml-4">
                @livewire('admin.users.create', ['setClient' => true])
            </div>
        </div>

        <div class="mt-12">
            @livewire('admin.users.show-all')
        </div>
    </div>
@endsection
