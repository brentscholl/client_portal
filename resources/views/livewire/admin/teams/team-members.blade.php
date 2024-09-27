<section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
    <x-card opened="{{ $cardOpened }}">
        <x-card.header title="Team Members" count="{{ $team->users->count() }}">
            @livewire('admin.teams.add-member', ['team' => $team], key('add-team-member'))
        </x-card.header>
        <x-card.body>
            @if($team->users->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($team->users as $user)
                        @livewire('admin.teams.team-member-card', ['user' => $user, 'team' => $team], key($user->id))
                    @endforeach
                </div>
            @else
                <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                    <x-svg.team class="mx-auto h-12 w-12 text-gray-200"/>
                    <span class="mt-2 block text-sm font-medium text-gray-300">
                      No team members assigned
                    </span>
                </span>
            @endif
        </x-card.body>
    </x-card>
</section>
