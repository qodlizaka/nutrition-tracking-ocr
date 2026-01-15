<x-layouts.app :title="__('Food label history')">

    <div class="w-full">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
            <flux:breadcrumbs.item href="{{ route('foods.index') }}">{{ __('Foods') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
            <div>
                <flux:heading size="xl">{{ __('Food label history') }}</flux:heading>
                <flux:subheading>{{ __('See all your previously scanned food labels.') }}</flux:subheading>
            </div>
        </div>

        <flux:separator class="my-6" />

        <livewire:food.browser source="mine" />

    </div>

</x-layouts.app>
