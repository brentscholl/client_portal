@extends('layouts.app')

@section('content')

    <div class="grid grid-cols-3 gap-4 px-8 pt-8">
        <div class="col-span-1">
            <div class="bg-white shadow overflow-hidden h-full sm:rounded-lg mt-4">
                <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                    <div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-no-wrap">
                        <div class="ml-4 mt-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Getting Started
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="p-10">
                    <ul role="list" class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-secondary-500 text-white flex items-center justify-center ring-8 ring-white">
                                            1
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex items center justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Create the new <a href="{{ route('admin.clients.index') }}" class="text-gray-900 font-medium">Client</a></p>
                                        </div>
                                        @livewire('admin.clients.create')
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-secondary-500 text-white flex items-center justify-center ring-8 ring-white">
                                            2
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex items center justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Create the new <a href="{{ route('admin.users.index') }}" class="text-gray-900 font-medium">Users</a> and assign to the Client</p>
                                        </div>
                                        @livewire('admin.users.create', ['setClient' => true])
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-secondary-500 text-white flex items-center justify-center ring-8 ring-white">
                                            3
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex items center justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Assign a Marketing Advisor for the Client</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-secondary-500 text-white flex items-center justify-center ring-8 ring-white">
                                            4
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex items center justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Create a new Project for the Client</p>
                                        </div>
                                        @livewire('admin.projects.create', ['setClient' => true, 'setService' => true, 'setPackage' => true])
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="relative pb-8">
                                {{--                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>--}}
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-secondary-500 text-white flex items-center justify-center ring-8 ring-white">
                                            5
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex items center justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Create new Tasks for the Project</p>
                                        </div>
                                        @livewire('admin.tasks.create', ['setClient' => true, 'setProject' => true, 'setPhase' => true])
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
{{--            <x-chart.login/>--}}
        </div>
        <div class="col-span-1">
            <x-chart.tasks class=""/>
        </div>
        <div class="col-span-1">
            <x-chart.projects class=""/>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 px-8 pb-12">
        @livewire('admin.activities.index-card', ['model' => null, 'showClientLabel' => true, 'cardOpened' => true, 'allowComments' => false], key('activity-index-card'))
        @livewire('admin.clients.show-all', ['simple' => true])
    </div>



    {{--    <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">--}}
{{--        @livewire('admin.users.show-all')--}}
{{--    </div>--}}

    @push('scripts')
        <!-- Charting library -->
        <script src="https://unpkg.com/chart.js@^2.9.3/dist/Chart.min.js"></script>
        <!-- Chartisan -->
        <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>

    @endpush
@endsection
