<div>
    <div class="flex space-x-1 text-sm font-medium text-gray-500">
        <x-svg.project class="h-5 w-5"/>
        <span>Projects</span>
    </div>
    <div class="flex space-x-6 mt-3">
        <div class="flex flex-col space-y-3 w-1/2 flex-grow-1">
            <label>Filter by Status</label>
            <x-input.checkbox-set :compact="true">
                <x-input.checkbox
                    wire:model="layout.{{$i}}.inputs.statuses.pending"
                    label="Pending"
                />
                <x-input.checkbox
                    wire:model="layout.{{$i}}.inputs.statuses.in-progress"
                    label="In Progress"
                />
                <x-input.checkbox
                    wire:model="layout.{{$i}}.inputs.statuses.completed"
                    label="Completed"
                />
                <x-input.checkbox
                    wire:model="layout.{{$i}}.inputs.statuses.on-hold"
                    label="On Hold"
                />
                <x-input.checkbox
                    wire:model="layout.{{$i}}.inputs.statuses.canceled"
                    label="Canceled"
                />
            </x-input.checkbox-set>
        </div>
    </div>
</div>
