<x-layouts.app :title="__('Foods')">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Select a food') }}</flux:heading>
                <flux:subheading>{{ __('Choose a food to add to your intake.') }}</flux:subheading>
            </div>
        </div>

        <flux:separator class="my-6" />

        <div class="mb-6">
            <form method="GET" action="{{ route('foods.index') }}" class="w-full flex gap-2">
                <flux:input
                    class="max-w-sm"
                    name="search"
                    value="{{ request('search') }}"
                    icon="magnifying-glass"
                    placeholder="{{ __('Search foods...') }}"
                    clearable
                />
                @if(request()->fullUrl() !== request()->url())
                    <flux:button variant="primary" href="{{ route('foods.index') }}">{{ __('Reset') }}</flux:button>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @forelse ($foods as $food)
                <div class="group relative flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white transition-all hover:-translate-y-1 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">

                    {{-- Card Image --}}
                    <div class="aspect-video w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($food->image)
                            <img src="{{ asset('storage/' . $food->image) }}"
                                 alt="{{ $food->name }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-zinc-300 dark:text-zinc-600">
                                <flux:icon.photo class="size-12 opacity-50" />
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="line-clamp-1 text-base font-semibold text-zinc-900 dark:text-zinc-100" title="{{ $food->name }}">
                                {{ $food->name }}
                            </h3>

                            <flux:dropdown>
                                <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" class="-mr-2 -mt-1 text-zinc-400 hover:text-zinc-600" />
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.item icon="trash" variant="danger">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </div>

                        <div class="mt-2 flex flex-wrap gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="flex items-center gap-1.5 rounded-md bg-zinc-50 px-2 py-1 dark:bg-zinc-800">
                                <flux:icon.scale class="size-4" />
                                <span>{{ $food->total_servings }} {{ Str::plural('serving', $food->total_servings) }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 rounded-md bg-zinc-50 px-2 py-1 dark:bg-zinc-800">
                                <flux:icon.tag class="size-4" />
                                <span>{{ $food->unit }}</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 text-xs text-zinc-400">
                            {{ __('Added') }} {{ $food->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-200 bg-zinc-50 py-12 dark:border-zinc-700 dark:bg-zinc-900/50">
                    <div class="rounded-full bg-white p-4 shadow-sm dark:bg-zinc-800">
                        <flux:icon.magnifying-glass class="size-8 text-zinc-400" />
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">
                        {{ __('No foods found') }}
                    </h3>
                    <p class="mt-1 text-zinc-500">
                        {{ request('search') ? __('Try adjusting your search terms.') : __('Get started by adding a new food.') }}
                    </p>
                    @if(request('search'))
                        <flux:button href="{{ route('foods.index') }}" variant="subtle" size="sm" class="mt-4">
                            {{ __('Clear Search') }}
                        </flux:button>
                    @endif
                </div>
            @endforelse

        </div>

        <div class="mt-8">
            {{ $foods->links() }}
        </div>
</x-layouts.app>
