<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] min-h-screen flex flex-col items-center p-6 lg:p-8 lg:justify-center">

        <header class="w-full lg:max-w-4xl mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-2">
                    @auth
                        <flux:button href="{{ url('/dashboard') }}" variant="subtle" size="sm">
                            {{ __('Dashboard') }}
                        </flux:button>
                    @else
                        <flux:button href="{{ route('login') }}" variant="ghost" size="sm">
                            {{ __('Log in') }}
                        </flux:button>

                        @if (Route::has('register'))
                            <flux:button href="{{ route('register') }}" variant="subtle" size="sm">
                                {{ __('Register') }}
                            </flux:button>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            {{ $slot }}
        </div>
    </body>
</html>
