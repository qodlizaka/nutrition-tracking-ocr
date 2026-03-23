<div class="w-full" data-testid="intake-index-main-container">

    <flux:breadcrumbs data-testid="intake-index-breadcrumbs">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl" data-testid="intake-index-heading">{{ __('Your Intake') }}</flux:heading>
            <flux:subheading>{{ __('Track your daily food consumption and nutritional intake.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6" data-testid="intake-index-controls">

        <div class="flex items-center gap-2 w-full sm:max-w-md">
            <flux:button
                wire:click="previousDate"
                icon="chevron-left"
                variant="subtle"
                square
                aria-label="Previous Day"
                data-testid="intake-index-previous-day-button"
            />

            <div class="flex-1">
                <flux:input
                    type="date"
                    wire:model.live="date"
                    max="{{ now()->format('Y-m-d') }}"
                    class="w-full"
                    data-testid="intake-index-date-input"
                />
            </div>

            <flux:button
                wire:click="nextDate"
                icon="chevron-right"
                variant="subtle"
                square
                :disabled="\Carbon\Carbon::parse($date)->isToday()"
                aria-label="Next Day"
                data-testid="intake-index-next-day-button"
            />
        </div>

        <flux:dropdown data-testid="intake-index-nutrition-dropdown">
            <flux:button icon-trailing="chevron-down" data-testid="intake-index-nutrition-dropdown-trigger">
                {{ __('Nutrition') }}
                <span class="ml-1 text-zinc-400 text-xs" data-testid="intake-index-nutrition-active-count">({{ count($activeNutritions) }})</span>
            </flux:button>

            <flux:menu keep-open data-testid="intake-index-nutrition-menu">
                @php
                    $currentCount = count($activeNutritions);
                    $isLimitReached = $currentCount >= 5;
                @endphp

                @foreach ($nutritions as $nutri)
                    @php
                        $isSelected = in_array($nutri->id, $activeNutritions);
                        $shouldDisable = $isLimitReached && !$isSelected;
                    @endphp

                    <flux:menu.checkbox
                        wire:key="nutri-checkbox-{{ $nutri->id }}-{{ $isSelected ? 'on' : 'off' }}"
                        wire:click="toggleNutrition({{ $nutri->id }})"
                        :checked="$isSelected"
                        :disabled="$shouldDisable"
                        data-testid="intake-index-nutrition-checkbox-{{ $nutri->id }}"
                    >
                        {{ ucfirst($nutri->name) }}

                        @if($shouldDisable)
                            <span class="ml-auto text-xs text-zinc-400 font-normal">{{ __('Max 5') }}</span>
                        @endif
                    </flux:menu.checkbox>
                @endforeach
            </flux:menu>
        </flux:dropdown>
    </div>

    <x-table.index data-testid="intake-index-table">
        <x-slot name="header">
            <x-table.heading data-testid="intake-index-th-no">No.</x-table.heading>

            <x-table.heading
                sortable
                wire:click="sort('created_at')"
                :direction="$sortBy === 'created_at' ? $sortDirection : null"
                data-testid="intake-index-th-time"
            >
                {{ __('Time') }}
            </x-table.heading>

            <x-table.heading data-testid="intake-index-th-notes">{{ __('Notes') }}</x-table.heading>

            @foreach ($this->nutritions as $nutri)
                @if(in_array($nutri->id, $activeNutritions))
                    @php
                        $sortKey = 'nutrition_' . $nutri->id;
                    @endphp

                    <x-table.heading
                        wire:key="th-nutri-{{ $nutri->id }}"
                        sortable
                        wire:click="sort('{{ $sortKey }}')"
                        :direction="$sortBy === '{{ $sortKey }}' ? $sortDirection : null"
                        data-testid="intake-index-th-nutri-{{ $nutri->id }}"
                    >
                        {{ __($nutri->name) }}
                    </x-table.heading>
                @endif
            @endforeach
        </x-slot>

        @forelse ($intakes as $intake)
            <x-table.row wire:key="intake-row-{{ $intake->id }}" data-testid="intake-index-row-{{ $intake->id }}">
                <x-table.cell data-testid="intake-index-cell-no-{{ $intake->id }}">
                    {{ $loop->iteration + ($intakes->currentPage() - 1) * $intakes->perPage() }}
                </x-table.cell>

                <x-table.cell data-testid="intake-index-cell-time-{{ $intake->id }}">
                    {{ $intake->created_at->format('H:i') }}
                </x-table.cell>

                <x-table.cell data-testid="intake-index-cell-notes-{{ $intake->id }}">
                    {{ $intake->notes ?? '-' }}
                </x-table.cell>

                @foreach ($this->nutritions as $nutri)
                    @if(in_array($nutri->id, $activeNutritions))
                        <x-table.cell wire:key="cell-{{ $intake->id }}-nutri-{{ $nutri->id }}" data-testid="intake-index-cell-nutri-{{ $intake->id }}-{{ $nutri->id }}">
                            {{ $intake->nutritions->find($nutri->id)?->pivot->value ?? '-' }}
                        </x-table.cell>
                    @endif
                @endforeach

            </x-table.row>
        @empty
            <x-table.row data-testid="intake-index-empty-row">
                <x-table.cell colspan="{{ 3 + count($activeNutritions) }}" class="text-center py-12" data-testid="intake-index-empty-cell">
                    <div class="flex flex-col items-center justify-center text-zinc-500">
                        <flux:icon.no-symbol class="size-12" />

                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('No intakes found') }}</span>
                        <p class="text-xs mt-1">{{ __('You haven\'t added any food for this date.') }}</p>
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table.index>

    <div class="mt-4" data-testid="intake-index-pagination">
        {{ $intakes->withQueryString()->links('pagination::tailwind') }}
    </div>

</div>
