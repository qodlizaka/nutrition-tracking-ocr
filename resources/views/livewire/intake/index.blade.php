<div class="w-full">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('intakes.index') }}">{{ __('Intake') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl">{{ __('Your Intake') }}</flux:heading>
            <flux:subheading>{{ __('Track your daily food consumption and nutritional intake.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">

        <div class="flex items-center gap-2 w-full sm:max-w-md">
            <flux:button
                wire:click="previousDate"
                icon="chevron-left"
                variant="subtle"
                square
                aria-label="Previous Day"
            />

            <div class="flex-1">
                <flux:input
                    type="date"
                    wire:model.live="date"
                    max="{{ now()->format('Y-m-d') }}"
                    class="w-full"
                />
            </div>

            <flux:button
                wire:click="nextDate"
                icon="chevron-right"
                variant="subtle"
                square
                :disabled="\Carbon\Carbon::parse($date)->isToday()"
                aria-label="Next Day"
            />
        </div>

        <flux:dropdown>
            <flux:button icon-trailing="chevron-down">
                {{ __('Nutrition') }}
                <span class="ml-1 text-zinc-400 text-xs">({{ count($activeNutritions) }})</span>
            </flux:button>

            <flux:menu keep-open>
                @foreach ($nutritions as $nutri)
                    @php
                        $isSelected = in_array($nutri->id, $activeNutritions);
                        $isLimitReached = count($activeNutritions) >= 5;
                        $shouldDisable = $isLimitReached && !$isSelected;
                    @endphp

                    <flux:menu.checkbox
                        wire:click="toggleNutrition({{ $nutri->id }})"
                        :checked="$isSelected"
                        :disabled="$shouldDisable"
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

    <x-table.index>
        <x-slot name="header">
            <x-table.heading>No.</x-table.heading>

            <x-table.heading
                sortable
                wire:click="sort('created_at')"
                :direction="$sortBy === 'created_at' ? $sortDirection : null"
            >
                {{ __('Time') }}
            </x-table.heading>

            <x-table.heading>{{ __('Notes') }}</x-table.heading>

            @foreach ($this->nutritions as $nutri)
                @if(in_array($nutri->id, $activeNutritions))
                    @php
                        $sortKey = 'nutrition_' . $nutri->id;
                    @endphp

                    <x-table.heading
                        sortable
                        wire:click="sort('{{ $sortKey }}')"
                        :direction="$sortBy === '{{ $sortKey }}' ? $sortDirection : null"
                    >
                        {{ __($nutri->name) }}
                    </x-table.heading>
                @endif
            @endforeach
        </x-slot>

        @forelse ($intakes as $intake)
            <x-table.row wire:key="{{ $intake->id }}">
                <x-table.cell>
                    {{ $loop->iteration + ($intakes->currentPage() - 1) * $intakes->perPage() }}
                </x-table.cell>

                <x-table.cell>
                    {{ $intake->created_at->format('H:i') }}
                </x-table.cell>

                <x-table.cell>
                    {{ $intake->notes ?? '-' }}
                </x-table.cell>

                @foreach ($this->nutritions as $nutri)
                    @if(in_array($nutri->id, $activeNutritions))
                        <x-table.cell>
                            {{ $intake->nutritions->find($nutri->id)?->pivot->value ?? '-' }}
                        </x-table.cell>
                    @endif
                @endforeach

            </x-table.row>
        @empty
            <x-table.row>
                <x-table.cell colspan="{{ 2 + count($activeNutritions) }}" class="text-center py-12">
                    <div class="flex flex-col items-center justify-center text-zinc-500">
                        <flux:icon.no-symbol class="size-12" />

                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('No intakes found') }}</span>
                        <p class="text-xs mt-1">{{ __('You haven\'t added any food for this date.') }}</p>
                    </div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table.index>

    <div class="mt-4">
        {{ $intakes->withQueryString()->links('pagination::tailwind') }}
    </div>

</div>
