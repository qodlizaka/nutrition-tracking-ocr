<section class="w-full">
    @include('partials.settings-heading')
    @use('App\Enum\PhysicalActivityLevel')

    <x-settings.layout :heading="__('Update personal info')" :subheading="__('Update your latest personal info here')">
        <form method="POST" wire:submit="updatePersonalInfo" class="mt-6 space-y-6">
            <flux:input
                wire:model="weight"
                label="{{ __('Weight') }}"
                type="number"
                required
                autocomplete="weight"
            />

            <flux:input
                wire:model="height"
                label="{{ __('Height') }}"
                type="number"
                required
                autocomplete="height"
            />

            <flux:select
                label="{{ __('Activity level') }}"
                wire:model.live="activityLevel">

                <flux:select.option value="null">
                    {{ __('Choose activity level') }}...
                </flux:select.option>
                @foreach (PhysicalActivityLevel::cases() as $activity)
                    <flux:select.option value="{{ $activity->value }}">
                        {{ $activity->getLabel() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            @if ($activityLevel)
                <flux:callout icon="information-circle">
                    <flux:callout.heading>
                        {{ $activityLevel->getLabel() }}
                    </flux:callout.heading>

                    <flux:callout.text>
                        {{ $activityLevel->getDescription() }}
                    </flux:callout.text>
                </flux:callout>
            @else
                <flux:text class="text-sm text-zinc-500 italic">
                    {{ __('Please select an activity level to see details.') }}
                </flux:text>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="personal-info-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
