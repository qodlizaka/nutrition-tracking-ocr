<flux:sidebar.nav class="mt-4">
    <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:sidebar.item>
        <flux:sidebar.item icon="settings" :href="route('settings.profile')" :current="request()->routeIs('settings.profile')" wire:navigate>{{ __('Settings') }}</flux:sidebar.item>
    </flux:navlist.group>

    <flux:navlist.group :heading="__('Intake')" class="grid">
        <flux:sidebar.item icon="utensils" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('My Intake') }}</flux:sidebar.item>
        <flux:sidebar.item icon="hamburger" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Foods') }}</flux:sidebar.item>
    </flux:navlist.group>

    <div class="mt-4 px-2">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="{{ __('Quick search food') }}..."
        />

        @if ($search !== '')
            <div class="mt-2 flex flex-col space-y-1">
                @forelse ($foods as $food)
                    <a
                        href="#"
                        class="group flex items-center justify-between rounded-lg px-2 py-2 text-left transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        wire:navigate
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
            </div>
        @endif
    </div>
</flux:sidebar.nav>
