@extends('layouts.app')

@section('title', 'Clients')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
        <div class="border-b pb-5 border-gray-200 sm:flex sm:items-center sm:justify-between">
            <h1>
                Clients
            </h1>
            <div class="mt-3 sm:mt-0 sm:ml-4">
                @livewire('admin.clients.create')
            </div>
        </div>

        <div class="mt-12">
            @livewire('admin.clients.show-all')
        </div>
    </div>
@endsection
