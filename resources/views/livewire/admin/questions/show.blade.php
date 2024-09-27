<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Questions" route="{{ route('admin.questions.index') }}">Questions</x-bread-crumb.link>
        <x-bread-crumb.link :current="true" route="{{ route('admin.questions.show', $question->id) }}"><x-svg.question class="h-5 w-5 text-gray-400" /><span>{{ Str::limit($question->body,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $question->body }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3 md:mt-0">
                                    @livewire('admin.questions.edit', ['question' => $question], key('question-edit'))
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($question->tagline)
                        <p class="text-sm flex space-x-2 items-center bg-gray-200 text-gray-500 rounded-md py-2 px-2 mt-6">
                            <x-svg.info-circle class="w-4 h-4 text-gray-400" />
                            <span>{{ $question->tagline }}</span>
                        </p>
                    @endif
                    @if($question->type != 'detail')
                        <section aria-labelledby="activity-title" class="mt-8 xl:mt-10 xl:border-b pb-6">
                            <div class="flex flex-col space-y-3 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                                <div class="flex-1 flex space-x-3 min-w-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <p class="text-left text-xl font-bold text-gray-900 truncate inline-flex items-center">
                                        Choices:
                                        <span tooltip="Question Type" class="ml-2 rounded-full bg-secondary-100 text-secondary-500 py-0.5 px-2 text-sm">
                                            @switch($question->type)
                                                @case('multi_choice')
                                                Multiple Choice
                                                @break
                                                @case('select')
                                                Select
                                                @break
                                                @case('boolean')
                                                Yes or No
                                                @break
                                            @endswitch
                                        </span>
                                    </p>
                                </div>
                                <ul class="flex flex-wrap space-x-2">
                                    @switch($question->type)
                                        @case('multi_choice')
                                        @case('select')
                                        @foreach(json_decode($question->choices) as $choice)
                                            <li class="rounded-full bg-gray-200 text-gray-900 py-1 px-4 mb-2">{{ $choice }}</li>
                                        @endforeach
                                        @break
                                        @case('boolean')
                                        <li class="rounded-full bg-gray-200 text-gray-900 py-1 px-4 mb-2">Yes</li>
                                        <li class="rounded-full bg-gray-200 text-gray-900 py-1 px-4 mb-2">No</li>
                                        @break
                                    @endswitch
                                </ul>
                            </div>
                        </section>
                    @endif
                    <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
                        <div class="flex flex-col space-y-3 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                            <div class="flex-1 flex space-x-3 min-w-0">
                                <x-svg.client class="h-10 w-10 text-gray-400"/>
                                <p class="text-left text-xl font-bold text-gray-900 truncate inline-flex items-center">
                                    {{ $question->client_id ? 'Client' : 'Clients' }} questioned:
                                </p>
                            </div>
                            <ul class="flex flex-wrap space-x-2">
                                @if($question->client_id)
                                    <li class="mb-2">
                                        <a tooltip="View Client" class="rounded-full bg-amber-200 text-amber-900 py-1 px-4 hover:bg-secondary-100 hover:text-secondary-500 transition-all duration-100 ease-in-out" href="{{ route('admin.clients.show', $question->client->id) }}">
                                            {{ $question->client->title }}
                                        </a>
                                    </li>
                                @else
                                    @foreach($clients as $client)
                                        <li class="mb-2">
                                            <a tooltip="View Client" class="rounded-full bg-amber-200 text-amber-900 py-1 px-4 hover:bg-secondary-100 hover:text-secondary-500 transition-all duration-100 ease-in-out" href="{{ route('admin.clients.show', $client->id) }}">
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </section>
                    @livewire('admin.answers.index-card', ['question' => $question, 'cardOpened' => true])
                </div>
                @include('admin.questions.question-sidebar', ['question' => $question])
            </div>
        </div>
    </main>
</div>

