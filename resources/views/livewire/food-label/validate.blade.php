<div class="w-full" data-testid="validate-main-container">
    <flux:breadcrumbs data-testid="validate-breadcrumbs">
        <flux:breadcrumbs.item href="{{ route('home') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('food.label.capture') }}">{{ __('Food label') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">{{ $food->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="lg:p-10 max-w-7xl mx-auto mt-4">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            {{-- Image & Details --}}
            <div class="space-y-6">
                <div class="relative aspect-square overflow-hidden rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm bg-zinc-100 dark:bg-zinc-900 group" data-testid="validate-image-container">
                    @if($food->image)
                        <img
                            src="{{ asset('storage/' . $food->image) }}"
                            alt="{{ $food->name }}"
                            class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                            data-testid="validate-food-image"
                        >
                    @else
                        <div class="flex items-center justify-center h-full text-zinc-400" data-testid="validate-no-image-placeholder">
                            No Image
                        </div>
                    @endif
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white mb-2" data-testid="validate-food-name-heading">
                        {{ $food->name }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        <span class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full border border-zinc-200 dark:border-zinc-700" data-testid="validate-serving-base-badge">
                            {{ __('Base') }}: {{ $food->serving_weight ?? 100 }}g {{ __('per serving') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="space-y-4">
                        <flux:input
                            label="{{ __('Review') }} {{ __('name') }}"
                            type="text"
                            wire:model="name"
                            icon:trailing="hamburger"
                            placeholder="{{ __('Food name') }}..."
                            data-testid="validate-name-input" />

                        <flux:input
                            label="{{ __('Review') }} {{ __('total serving') }}"
                            type="number"
                            step="any"
                            wire:model="totalServing"
                            icon:trailing="scale"
                            placeholder="{{ __('Food total serving') }}..."
                            data-testid="validate-total-serving-input" />

                        <flux:input
                            label="{{ __('Review') }} {{ __('unit') }}"
                            type="text"
                            wire:model.live="unit"
                            icon:trailing="ruler"
                            placeholder="{{ __('Food unit') }}..."
                            data-testid="validate-unit-input" />

                        <flux:button wire:click="saveFood()" variant="primary" class="w-full" data-testid="validate-submit-button">
                            {{ __('Next step') }}
                        </flux:button>
                    </div>
                </div>
            </div>

            <livewire:extra.nutrition-fact-table
                :food="$this->food"
                :editable="true"
                />
        </div>
    </div>
</div>
