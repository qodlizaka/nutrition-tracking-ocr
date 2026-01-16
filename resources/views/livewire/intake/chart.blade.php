<div class="w-full">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('intakes.chart') }}">{{ __('Chart') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl">{{ __('Nutrition charts') }}</flux:heading>
            <flux:subheading>{{ __('Visualize your nutritional intake over time.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

</div>
