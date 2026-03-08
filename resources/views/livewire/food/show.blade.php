<div class="w-full" data-testid="food-show-main-container">
    <flux:breadcrumbs data-testid="food-show-breadcrumbs">
        <flux:breadcrumbs.item href="{{ route('home') }}" icon="home" />
        <flux:breadcrumbs.item href="{{ route('foods.index') }}">{{ __('Foods') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="#">{{ $food->name }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="lg:p-10 max-w-7xl mx-auto mt-4">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            {{-- Image & Details --}}
            <div class="space-y-6">
                <div class="relative aspect-square overflow-hidden rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm bg-zinc-100 dark:bg-zinc-900 group" data-testid="food-show-image-container">
                    @if($food->image)
                        <img
                            src="{{ asset('storage/' . $food->image) }}"
                            alt="{{ $food->name }}"
                            class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                            data-testid="food-show-image"
                        >
                    @else
                        <div class="flex items-center justify-center h-full text-zinc-400" data-testid="food-show-no-image-placeholder">
                            No Image
                        </div>
                    @endif
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-zinc-900 dark:text-white mb-2" data-testid="food-show-title">
                        {{ $food->name }}
                    </h1>
                    <div class="flex items-center gap-4 text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        <span class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full border border-zinc-200 dark:border-zinc-700" data-testid="food-show-serving-base-badge">
                            Base: {{ $food->serving_weight ?? 100 }}g per serving
                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="space-y-4">
                        <flux:label>{{ __('Measurement mode') }}</flux:label>

                        <flux:radio.group variant="segmented" class="mt-2" data-testid="food-show-measurement-radio-group">
                            @foreach ($measurements as $m)
                                <flux:radio
                                    wire:key="measure-{{ $m->name }}"
                                    :checked="$this->measure->name === $m->name"
                                    wire:click="setMeasure('{{ $m->name }}')"
                                    class="hover:cursor-pointer"
                                    label="{{ $m->getName() }}"
                                    icon="{{ $m->getIcon() }}"
                                    data-testid="food-show-measurement-radio-{{ \Illuminate\Support\Str::slug($m->name) }}" />
                            @endforeach
                        </flux:radio.group>

                        <flux:input
                            label="Enter {{ $measure->getName() }}"
                            type="number"
                            wire:model.live="amount"
                            min="0"
                            icon:trailing="{{ $measure->getIcon() }}"
                            placeholder="0"
                            data-testid="food-show-amount-input" />

                        <flux:modal.trigger name="consume-food-modal">
                            <flux:button variant="primary" class="w-full" data-testid="food-show-trigger-consume-modal">{{ __('Consume') }}</flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>

            <livewire:extra.nutrition-fact-table
                :food="$this->food"
                :multiplier="$this->measure->getMultiplier()"
                :key="'nutrition-table-'.$this->measure->getMultiplier()"
                :editable="false"
                />
        </div>
    </div>

    <flux:modal name="consume-food-modal" class="md:w-96" data-testid="food-show-consume-modal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Consume the Food') }}</flux:heading>
                <flux:text class="mt-2">{{ __('This action will record your food intake. It`s irreversible. Are you sure?') }}</flux:text>
            </div>

            <div>
                <flux:label class="block">{{ __('Amount') }}</flux:label>
                <flux:input.group class="mt-1.5">
                    <flux:input
                        type="text"
                        value="{{ $amount * $this->measure->getBaseMultiplier() }}"
                        min="0"
                        placeholder="0"
                        disabled
                        readonly
                        data-testid="food-show-modal-calculated-amount-input"
                    />
                    <flux:input.group.suffix data-testid="food-show-modal-unit-suffix">{{ $this->measure->getUnit() }}</flux:input.group.suffix>
                </flux:input.group>
            </div>

            <flux:input
                label="{{ __('Consumed at') }}"
                type="datetime-local"
                wire:model="consumedAt"
                data-testid="food-show-modal-consumed-at-input"
            />

            <flux:textarea
                label="{{ __('Notes') }}"
                placeholder="{{ __('Optional') }}..."
                wire:model="notes"
                data-testid="food-show-modal-notes-textarea"
            />

            <div class="flex">
                <flux:spacer />

                <flux:button
                    type="button"
                    variant="primary"
                    wire:click="consumeFood()"
                    data-testid="food-show-modal-confirm-button"
                >
                    {{ __('Confirm Consumption') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
