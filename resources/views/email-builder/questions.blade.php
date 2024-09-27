<div>
    <div class="flex space-x-1 text-sm font-medium text-gray-500">
        <x-svg.question class="h-5 w-5"/>
        <span>Questions</span>
    </div>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-3 w-1/2 flex-grow-1">
            <div>
                <x-input.email-builder.project-select
                    :project="$layout[$i]['inputs']['project']"
                    :projects="$layout[$i]['inputs']['projects']"
                    :i="$i"
                />
            </div>
        </div>
        <div class="flex flex-col space-y-3 w-1/2 flex-grow-1">
            <div>
                <x-input.checkbox-set :compact="true">
                    <x-input.checkbox
                        wire:model="layout.{{$i}}.inputs.include_onboarding"
                        label="Include Onboarding Questions"
                    />
                </x-input.checkbox-set>
            </div>
            <div>
                <label class="mb-3 mt-1">Filter by Answer</label>
                <x-input.checkbox-set :compact="true">
                    <x-input.checkbox
                        wire:model="layout.{{$i}}.inputs.filter"
                        label="All"
                        value="all"
                        type="radio"
                    />
                    <x-input.checkbox
                        wire:model="layout.{{$i}}.inputs.filter"
                        label="Unanswered"
                        value="unanswered"
                        type="radio"
                    />
                    <x-input.checkbox
                        wire:model="layout.{{$i}}.inputs.filter"
                        label="Answered"
                        value="answered"
                        type="radio"
                    />
                </x-input.checkbox-set>
            </div>
        </div>
    </div>
</div>
