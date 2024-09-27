@section('alert')
    <x-alert.saved message="Password updated!"></x-alert.saved>
@endsection
<div>
    <form wire:submit.prevent="save" class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
        <x-input.col.text
            wire:model.lazy="current_password"
            type="password"
            label="Current password"
            :required="true"
            placeholder="Enter your current password"
            class=""
        />
        <x-input.col.text
            wire:model.lazy="new_password"
            type="password"
            label="New password"
            :required="true"
            placeholder="Enter your new password"
            class=""
        />
        <x-input.col.text
            wire:model.lazy="new_password_confirmation"
            type="password"
            label="Confirm new password"
            :required="true"
            placeholder="Enter your new password again"
            class=""
        />
        <div class="pt-5 sm:border-t sm:border-gray-200">
            <div class="flex justify-end">
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update Password
                </button>
            </div>
        </div>
    </form>
</div>
