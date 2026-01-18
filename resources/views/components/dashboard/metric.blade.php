@props([
    'label',
    'value',
    'unit' => null,
    'tooltip' => null,
    'trend' => null,
])

@php
    $trendIcon = null;
    $trendColor = null;
    $trendValue = null;

    if ($trend !== null) {
        $trendValue = abs(round($trend, 0));
        if ($trend > 0) {
            $trendIcon = 'trending-up';
            $trendColor = 'text-green-500';
        } elseif ($trend < 0) {
            $trendIcon = 'trending-down';
            $trendColor = 'text-red-500';
        } else {
            $trendIcon = 'minus';
            $trendColor = 'text-zinc-500';
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 flex flex-col items-center justify-center aspect-square gap-3 text-center']) }}>

    <flux:subheading class="flex items-center gap-0.5">
        {{ $label }}

        @if($tooltip)
            <flux:tooltip>
                <flux:button icon="information-circle" size="xs" variant="ghost" />
                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                    <p>{{ $tooltip }}</p>
                </flux:tooltip.content>
            </flux:tooltip>
        @endif
    </flux:subheading>

    <div class="flex flex-col">
        <flux:heading level="2" class="text-4xl! mb-0!">{{ $value }}</flux:heading>

        @if($unit)
            <flux:subheading>{{ $unit }}</flux:subheading>
        @endif

        @if($trend !== null)
            <div class="flex items-center gap-1 {{ $trendColor }}">
                <flux:icon name="{{ $trendIcon }}" size="sm" />
                <flux:subheading class="!text-sm">
                    {{ $trendValue }} {{ $unit }}
                </flux:subheading>
            </div>
        @endif
    </div>
</div>
