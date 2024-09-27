@extends('layouts.app')

@section('title', 'Services')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="border-b pb-5 border-gray-200 sm:flex sm:items-center sm:justify-between">
                <h1>
                    Services
                </h1>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($services as $service)
                    <a tooltip="View Service" href="{{ route('admin.services.show', $service->id) }}" class="group col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200 transition ease-in-out duration-150 border border-white hover:shadow-lg hover:border-secondary-500">
                        <div class="flex-1 flex flex-col p-8">
                            <x-dynamic-component :component="'svg.service.'.$service->slug" class="w-32 h-32 text-secondary-500 mx-auto" />
                            <h3 class="mt-6 text-gray-900 text-sm font-medium group-hover:text-secondary-500 transition ease-in-out duration-150">{{ $service->title }}</h3>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="w-0 flex-1 flex">
                                    <span tooltip="Packages" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg">
                                        <x-svg.package class="h-5 w-5 text-gray-400" />
                                        <span class="ml-3">{{ $service->packages->count() }}</span>
                                    </span>
                                </div>
                                <div class="-ml-px w-0 flex-1 flex">
                                    <span tooltip="Clients" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="ml-3">{{ $service->clients->count() }}</span>
                                    </span>
                                </div>
                                <div class="-ml-px w-0 flex-1 flex">
                                    <span tooltip="Projects" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span class="ml-3">{{ $service->projects->count() }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
