<flux:sidebar.nav class="mt-4">
    @php
        $shouldNavigate = ! request()->routeIs(['food.label.capture']);
    @endphp
    <flux:navlist.group :heading="__('Platform')" class="grid">
        @if(auth()->user()->isAdmin())
            <flux:sidebar.item icon="home" :href="route('filament.admin.pages.dashboard')" :current="request()->routeIs('filament.admin.pages.dashboard')">{{ __('Admin panel') }}</flux:sidebar.item>
        @endif
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" :wire:navigate="$shouldNavigate">{{ __('Dashboard') }}</flux:sidebar.item>
        <flux:sidebar.item icon="settings" :href="route('settings.profile')" :current="request()->routeIs('settings.profile')" :wire:navigate="$shouldNavigate">{{ __('Settings') }}</flux:sidebar.item>
    </flux:navlist.group>

    <flux:navlist.group :heading="__('Intake')" class="grid">
        <flux:sidebar.item icon="utensils" :href="route('intakes.index')" :current="request()->routeIs('intakes.index')" :wire:navigate="$shouldNavigate">{{ __('My Intake') }}</flux:sidebar.item>
        <flux:sidebar.item icon="chart-pie" :href="route('intakes.chart')" :current="request()->routeIs('intakes.chart')" :wire:navigate="$shouldNavigate">{{ __('Charts') }}</flux:sidebar.item>
        <flux:sidebar.item icon="hamburger" :href="route('foods.index')" :current="request()->routeIs('foods.index')" :wire:navigate="$shouldNavigate">{{ __('Foods') }}</flux:sidebar.item>

        <flux:sidebar.group expandable heading="{{ __('Food label') }}" class="grid">
            <flux:sidebar.item icon="camera" :href="route('food.label.capture')" :current="request()->routeIs('food.label.capture')">{{ __('Capture') }}</flux:sidebar.item>
            <flux:sidebar.item icon="clock" :href="route('food.label.history')" :current="request()->routeIs('food.label.history')" :wire:navigate="$shouldNavigate">{{ __('History') }}</flux:sidebar.item>
        </flux:sidebar.group>
    </flux:navlist.group>

    <flux:navlist.group :heading="__('Tutorial')" class="grid">
        <flux:sidebar.item icon="youtube" href="" :current="false">{{ __('User') }}</flux:sidebar.item>
    </flux:navlist.group>

    <div class="mt-4 px-2">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Quick search food') }}..."
            clearable
        />

        @if ($search !== '')
            <div class="mt-2 flex flex-col space-y-1">
                @forelse ($foods as $food)
                    <a
                        href="{{ route('foods.show', $food->id) }}"
                        class="group flex items-center justify-between rounded-lg px-2 py-2 text-left transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    >
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $food->name }}
                            </p>
                            <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $food->nutritions->filter(fn($n) => $n->name === 'energy')->first()->pivot->value ?? 0 }} kcal
                            </p>
                        </div>

                        <flux:icon.chevron-right class="size-3 text-zinc-400 opacity-0 transition-opacity group-hover:opacity-100" />
                    </a>
                @empty
                    <div class="px-2 py-3 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('No foods found.') }}
                    </div>
                @endforelse

                @if ($foods->count() >= 5)
                    <div class="pt-2 mt-1 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button
                            href="{{ route('foods.index', ['search' => $search]) }}"
                            variant="ghost"
                            size="sm"
                            class="w-full justify-center text-xs"
                            :wire:navigate="$shouldNavigate"
                        >
                            {{ __('Show all results') }}
                        </flux:button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</flux:sidebar.nav>
