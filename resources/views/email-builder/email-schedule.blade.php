<div
    x-data="{
            'setSendDate': {{ $email_schedule['set_send_date'] }},
            'repeat': '{{ $email_schedule['repeat'] }}',
            'repeatEveryType': '{{ $email_schedule['repeat_custom']['repeats_every']['type'] }}',
            'repeatEveryItem': '{{ $email_schedule['repeat_custom']['repeats_every']['item'] }}',
            'repeatEnds': '{{ $email_schedule['repeat_custom']['ends'] }}',
        }"
    class="rounded-md bg-gray-50 p-4 flex flex-col space-y-4 border border-2 border-gray-200 border-dashed shadow-inner"
>
    <div>
        <x-input.checkbox-set :compact="true">
            <x-input.checkbox
                wire:model="schedule_email_to_send"
                label="Set Email Schedule"
                description="Schedule email to send at a later date or set repeat automatic emailing schedule"
            />
        </x-input.checkbox-set>
    </div>
    @if($schedule_email_to_send)

        <div wire:ignore>
            <x-input.radio-set index="{{ $email_schedule['set_send_date'] == 1 ? 0 : 1 }}" label="Send Date">
                <x-input.radio
                    wire:model.lazy="email_schedule.set_send_date"
                    value="1"
                    label="Send on Date"
                    description="Email will send on date picked at 9am. If set to repeat, following emails will send at 9am"
                    :first="true"
                />
                <x-input.radio
                    x-model="setSendDate"
                    wire:model.lazy="email_schedule.set_send_date"
                    value="0"
                    label="Send Immediately"
                    description="Email will send immediately. If set to repeat, following emails will send at 9am"
                    :last="true"
                />
            </x-input.radio-set>
        </div>

        <!-- Start Date -->
        <div x-cloak x-show="setSendDate">
            <x-input.date
                wire:model.lazy="email_schedule.send_date"
                label="Send Date"
                :minDate="\Carbon\Carbon::tomorrow()->format('Y-m-d H:i:s')"
            />
        </div>

        <!-- Repeat -->
        <x-input.select
            wire:model.lazy="email_schedule.repeat"
            x-model="repeat"
            label="Set repeat"
        >
            <option value="does-not-repeat">Does not repeat</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly on {{ $email_schedule["send_date_day"] }}</option>
            <option value="monthly">Monthly on
                the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_schedule["send_date_week"]) }} {{ $email_schedule["send_date_day"] }}</option>
            <option value="weekday">Every weekday (Monday to Friday)</option>
            <option value="custom">Custom...</option>
        </x-input.select>

        <!------------- Custom Repeater -------------->
    <div>
        @if($email_schedule['repeat'] == 'custom')
            <div class="custom-schedule-builder flex flex-col space-y-6">
                <div>
                    <div class="repeat-every">
                        <label>Repeat every</label>
                        <div class="flex space-x-2 w-1/2">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_every.item"
                                x-model="repeatEveryItem"
                                @error('email_schedule.repeat_custom.repeats_every.item')
                                class="form-input-container__input form-input-container__input--has-error"
                                aria-invalid="true"
                                aria-describedby="error"
                                @else
                                class="form-input-container__input"
                                @endif
                                type="number" min="1" max="100">
                            <select
                                wire:model.lazy="email_schedule.repeat_custom.repeats_every.type"
                                x-model="repeatEveryType"
                                @error("email_schedule.repeat_custom.repeats_every.type")
                                class="form-input-container__input form-input-container__input--has-error"
                                aria-invalid="true"
                                aria-describedby="error"
                                @else
                                class="form-input-container__input"
                                @endif
                            >
                                <option value="day" x-text="repeatEveryItem > 1 ? 'days' : 'day'">day</option>
                                <option value="week" x-text="repeatEveryItem > 1 ? 'weeks' : 'week'">week</option>
                                <option value="month" x-text="repeatEveryItem > 1 ? 'months' : 'month'">month</option>
                            </select>
                        </div>
                    </div>
                    @error('email_schedule.repeat_custom.repeats_every.item')
                    <span wire:key="error_email_schedule.repeat_custom.repeats_every.item" class="form-input-container__input__error-message">{{ $message }}</span>
                    @enderror
                    @error('email_schedule.repeat_custom.repeats_every.type')
                    <span wire:key="error_email_schedule.repeat_custom.repeats_every.type" class="form-input-container__input__error-message">{{ $message }}</span>
                    @enderror
                </div>

                @if($email_schedule['repeat_custom']['repeats_every']['type'] == 'week')
                    <div class="flex mb-4 space-x-4">
                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.sunday"
                                type="checkbox" id="repeat-sunday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-sunday" class="repeat-day">S</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.monday"
                                type="checkbox" id="repeat-monday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-monday" class="repeat-day">M</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.tuesday"
                                type="checkbox" id="repeat-tuesday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-tuesday" class="repeat-day">T</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.wednesday"
                                type="checkbox" id="repeat-wednesday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-wednesday" class="repeat-day">W</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.thursday"
                                type="checkbox" id="repeat-thursday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-thursday" class="repeat-day">T</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.friday"
                                type="checkbox" id="repeat-friday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-friday" class="repeat-day">F</label>
                        </div>

                        <div class="repeat-checkbox">
                            <input
                                wire:model.defer="email_schedule.repeat_custom.repeats_weekly_on.saturday"
                                type="checkbox" id="repeat-saturday" class="hide-checkbox" style="display: none;">
                            <label for="repeat-saturday" class="repeat-day">S</label>
                        </div>
                    </div>
                @elseif($email_schedule['repeat_custom']['repeats_every']['type'] == 'month')
                    <div class="mb-4 w-1/2">
                        <select
                            wire:model.defer="email_schedule.repeat_custom.repeats_monthly_on.day"
                            @error('email_schedule.repeat_custom.repeats_monthly_on.day')
                            class="form-input-container__input form-input-container__input--has-error"
                            aria-invalid="true"
                            aria-describedby="error"
                            @else
                            class="form-input-container__input"
                            @endif
                        >
                            <option value="date">Monthly on
                                the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_schedule["send_date_number"]) }}</option>
                            <option value="day">Monthly on
                                the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_schedule["send_date_week"]) }} {{ $email_schedule["send_date_day"] }}</option>
                        </select>
                    </div>
            @endif

            <!-- Repeat Ends -->
                <div class="repeat-ends flex flex-col space-y-3">
                    <label>Ends</label>
                    <div class="repeat-ends-never flex space-x-2 items-center">
                        <input
                            wire:model.defer="email_schedule.repeat_custom.ends"
                            value="never"
                            x-model="repeatEnds"
                            type="radio" id="repeat-ends-never">
                        <label for="repeat-ends-never">Never</label>
                    </div>
                    <div class="repeat-ends-on flex space-x-2 items-center">
                        <input
                            wire:model.lazy="email_schedule.repeat_custom.ends"
                            value="ends_on"
                            x-model="repeatEnds"
                            type="radio" id="repeat-ends-on">
                        <label for="repeat-ends-never">On date</label>

                        <div x-show="repeatEnds == 'ends_on'" x-cloak>
                            <x-input.date
                                dateType="end"
                                wire:model.lazy="email_schedule.repeat_custom.end_date"
                                :minDate="\Carbon\Carbon::parse($email_schedule['send_date'])->addDay()->format('Y-m-d H:i:s')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>
