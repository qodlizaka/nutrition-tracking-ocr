@use('App\Enum\NutritionGroup')

<div data-testid="food-browser-container">
    <div class="mb-6 flex gap-2 w-full max-w-sm">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Search foods...') }}"
            clearable
            data-testid="food-browser-search-input"
        />

        <flux:dropdown data-testid="food-browser-nutrition-dropdown">
            <flux:button icon-trailing="chevron-down" data-testid="food-browser-nutrition-dropdown-trigger">
                {{ __('Nutrition') }}
                <span class="ml-1 text-zinc-400 text-xs" data-testid="food-browser-nutrition-active-count">({{ count($activeNutritions) }})</span>
            </flux:button>

            <flux:menu keep-open data-testid="food-browser-nutrition-menu">
                @foreach ($nutritions as $nutri)
                    @php
                        $isSelected = in_array($nutri->name, $activeNutritions);
                        $isLimitReached = count($activeNutritions) >= 5;
                        $shouldDisable = $isLimitReached && !$isSelected;
                    @endphp

                    <flux:menu.checkbox
                        wire:key="filter-nutri-{{ $nutri->name }}"
                        wire:click="toggleNutrition('{{ $nutri->name }}')"
                        :checked="$isSelected"
                        :disabled="$shouldDisable"
                        data-testid="food-browser-filter-checkbox-{{ Str::slug($nutri->name) }}"
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

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" data-testid="food-browser-grid">

        @forelse ($foods as $food)
            <div wire:key="food-card-{{ $food->id }}" class="group relative flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white transition-all hover:-translate-y-1 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900" data-testid="food-browser-card-{{ $food->id }}">

                <div class="aspect-video w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                    @if($food->image)
                        <img src="{{ asset('storage/' . $food->image) }}"
                            alt="{{ $food->name }}"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            data-testid="food-browser-card-image-{{ $food->id }}">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-zinc-300 dark:text-zinc-600" data-testid="food-browser-card-placeholder-{{ $food->id }}">
                            <flux:icon.photo class="size-12 opacity-50" />
                        </div>
                    @endif
                </div>

                <div class="flex flex-1 flex-col p-4">
                    <div class="flex items-start justify-between gap-4">
                        <h3 class="line-clamp-1 text-base font-semibold text-zinc-900 dark:text-zinc-100 hover:underline" title="{{ $food->name }}">
                            <a href="{{ route('foods.show', $food->id) }}" data-testid="food-browser-card-title-link-{{ $food->id }}">
                                {{ $food->name }}
                            </a>
                        </h3>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="flex items-center gap-1.5 rounded-md bg-zinc-50 px-2 py-1 dark:bg-zinc-800" data-testid="food-browser-card-servings-{{ $food->id }}">
                            <flux:icon.scale class="size-4" />
                            <span>{{ $food->total_servings }}{{ $food->unit }}/{{ __('serving') }}</span>
                        </div>

                        @foreach ($activeNutritions as $nutriName)
                            @php
                                $baseNutrient = $nutritions->get($nutriName);
                                $foodNutrient = $food->nutritions->firstWhere('name', $nutriName);
                                $val = $foodNutrient ? $foodNutrient->pivot->value : 0;
                                $unit = $nutritions[$nutriName]->unit ?? '';
                            @endphp

                            <div wire:key="food-{{ $food->id }}-nutri-{{ $nutriName }}" class="flex items-center gap-1.5 rounded-md bg-zinc-50 px-2 py-1 dark:bg-zinc-800 transition-all" data-testid="food-browser-card-nutri-{{ $food->id }}-{{ Str::slug($nutriName) }}">
                                @switch($baseNutrient->group)
                                    @case(NutritionGroup::Mineral)
                                        <flux:icon.stone class="size-4 text-zinc-400" />
                                        @break
                                    @case(NutritionGroup::Vitamin)
                                        <flux:icon.pill class="size-4 text-zinc-400" />
                                        @break
                                    @default
                                        <flux:icon.zap class="size-4 text-zinc-400" />
                                @endswitch
                                <span>
                                    {{ $baseNutrient->name }} {{ $val }} <span class="text-xs text-zinc-500">{{ $unit }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto pt-4 text-xs text-zinc-400" data-testid="food-browser-card-date-{{ $food->id }}">
                        {{ __('Added') }} {{ $food->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-zinc-50 py-12 dark:border-zinc-700 dark:bg-zinc-900/50" data-testid="food-browser-empty-state">
                <div class="rounded-full bg-white p-4 shadow-sm dark:bg-zinc-800">
                    <flux:icon.magnifying-glass class="size-8 text-zinc-400" />
                </div>
                <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $this->source === 'public' ? __('No foods found') : __('No food labels found') }}
                </h3>
                <p class="mt-1 text-zinc-500">
                    {{ $search
                        ? __('Try adjusting your search terms.')
                        : ($this->source === 'public'
                            ? __('Get started by adding a new food.')
                            : __('Get started by scanning a food label.')) }}
                </p>

                @if($search)
                    <flux:button wire:click="$set('search', '')" variant="subtle" size="sm" class="mt-4" data-testid="food-browser-clear-search-button">
                        {{ __('Clear Search') }}
                    </flux:button>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-8" data-testid="food-browser-pagination">
        {{ $foods->links('pagination::tailwind') }}
    </div>
</div>
