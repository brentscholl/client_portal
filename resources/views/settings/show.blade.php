@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="relative max-w-4xl mx-auto md:px-8 xl:px-0">
        <div class="pt-10 pb-16">
            <div class="px-4 sm:px-6 md:px-0">
                <h1 class="text-3xl leading-9 font-extrabold text-gray-900">Settings</h1>
            </div>
            <div class="px-4 sm:px-6 md:px-0">
                <div class="py-6">
                    <!-- Tabs -->
                    <div class="lg:hidden">
                        <select aria-label="Selected tab"
                            class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">

                            <option {{ Request::is('settings') ? 'selected=""' : '' }}>General</option>

                            <option {{ Request::is('settings/password') ? 'selected=""' : '' }}>Password</option>

                            <option {{ Request::is('settings/notifications') ? 'selected=""' : '' }}>Notifications</option>

                        </select>
                    </div>
                    <div class="hidden lg:block">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">

                                <a href="{{ route('settings.general') }}" class="{{ Request::routeIs('settings.general') ?
                                    'border-secondary-500 text-secondary-600 focus:text-secondary-800 focus:border-secondary-700 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:border-gray-300 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none'
                                    }}">
                                    General
                                </a>

                                <a href="{{ route('settings.password') }}" class="{{ Request::routeIs('settings.password') ?
                                    'border-secondary-500 text-secondary-600 focus:text-secondary-800 focus:border-secondary-700 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:border-gray-300 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none'
                                    }}">
                                    Password
                                </a>

                                <a href="{{ route('settings.notifications') }}" class="{{ Request::routeIs('settings.notifications') ?
                                    'border-secondary-500 text-secondary-600 focus:text-secondary-800 focus:border-secondary-700 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:border-gray-300 whitespace-no-wrap py-4 px-1 border-b-2 font-medium text-sm leading-5 focus:outline-none'
                                    }}">
                                    Notifications
                                </a>
                            </nav>
                        </div>
                    </div>
                    @yield('settings-content')
                </div>
            </div>
        </div>
    </div>
@endsection
