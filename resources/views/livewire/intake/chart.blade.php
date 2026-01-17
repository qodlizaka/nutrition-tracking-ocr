@use("Illuminate\Support\Str")

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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($macroNutrients as $nutrition)
            @php
                $item = $chartData->get($nutrition->id);
                $labels = $item->keys()->toArray();
                $data = $item->values()->toArray();
            @endphp

            <livewire:extra.chart.date-range-data-chart
                chartId="{{ Str::snake($nutrition->name) }}_chart"
                :data="$data"
                :labels="$labels"
                title="{{ $nutrition->name }}"
                :limit="$this->userAkg->nutritions->find($nutrition->id)?->pivot->value ?? null" />
        @endforeach
    </div>
</div>
