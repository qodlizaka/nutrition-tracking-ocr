<x-layouts.app :title="__('Foods data')">

    <div class="w-full">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
            <flux:breadcrumbs.item href="{{ route('foods.index') }}">{{ __('Foods') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
            <div>
                <flux:heading size="xl">{{ __('Select a food') }}</flux:heading>
                <flux:subheading>{{ __('Choose a food to add to your intake.') }}</flux:subheading>
            </div>
        </div>

        <flux:separator class="my-6" />

        <livewire:food.browser source="public" />

    </div>

</x-layouts.app>
