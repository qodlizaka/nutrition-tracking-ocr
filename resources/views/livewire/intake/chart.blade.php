<div class="w-full">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('intakes.chart') }}">{{ __('Chart') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl">{{ __('Your Intake') }}</flux:heading>
            <flux:subheading>{{ __('Track your daily food consumption and nutritional intake.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

</div>
