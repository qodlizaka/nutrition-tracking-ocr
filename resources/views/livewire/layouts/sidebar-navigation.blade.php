<flux:sidebar.nav class="mt-4">
    <flux:input icon="magnifying-glass" placeholder="{{ __('Quick search food') }}..." />
    <flux:navlist.group :heading="__('Platform')" class="grid mt-4">
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:sidebar.item>
        <flux:sidebar.item icon="settings" :href="route('settings.profile')" :current="request()->routeIs('settings.profile')" wire:navigate>{{ __('Settings') }}</flux:sidebar.item>
    </flux:navlist.group>
    <flux:navlist.group :heading="__('Intake')" class="grid">
        <flux:sidebar.item icon="utensils" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('My Intake') }}</flux:sidebar.item>
        <flux:sidebar.item icon="hamburger" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Foods') }}</flux:sidebar.item>
        <flux:sidebar.item icon="photo" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Nutrition Label') }}</flux:sidebar.item>
    </flux:navlist.group>
</flux:sidebar.nav>
