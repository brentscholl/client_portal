@props([
'title' => '',
])
<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
    <dt class="text-sm leading-5 font-medium text-gray-500">
        {{ $title }}
    </dt>

    <dd class="sm:mt-0 sm:col-span-2">
        <form wire:submit.prevent="resetPassword" class="flex flex-col space-y-4 items-center text-sm leading-5 text-gray-900">
            <span class="flex-grow">
                <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                    <input x-ref="textInput" type="password" placeholder="Current Password" id="" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                </div>
            </span>
            <span class="flex-grow">
                <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                    <input x-ref="textInput" type="password" id="" placeholder="New Password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                </div>
            </span>
            <span class="flex-grow">
                <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                    <input x-ref="textInput" type="password" id="" placeholder="Confirm New Password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                </div>
            </span>
            <span class="flex-grow">
                <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                    <button type="submit" class="btn-primary">
                        Update Password
                    </button>
                </div>
            </span>
        </form>
    </dd>
</div>
