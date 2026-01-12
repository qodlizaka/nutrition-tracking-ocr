@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

        {{-- Mobile View: Simple Previous / Next --}}
        <div class="flex flex-1 justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <flux:button disabled variant="subtle">{{ __('Previous') }}</flux:button>
            @else
                {{-- Changed href to wire:click --}}
                <flux:button wire:click="previousPage" wire:loading.attr="disabled" variant="subtle">{{ __('Previous') }}</flux:button>
            @endif

            @if ($paginator->hasMorePages())
                {{-- Changed href to wire:click --}}
                <flux:button wire:click="nextPage" wire:loading.attr="disabled" variant="subtle">{{ __('Next') }}</flux:button>
            @else
                <flux:button disabled variant="subtle">{{ __('Next') }}</flux:button>
            @endif
        </div>

        {{-- Desktop View: Info Text + Page Numbers --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">

            {{-- Information Text --}}
            <div>
                <p class="text-sm text-zinc-700 dark:text-zinc-300">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- Buttons Group --}}
            <div class="flex gap-1">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <flux:button disabled icon="chevron-left" variant="subtle" size="sm" square />
                @else
                    {{-- Changed href to wire:click --}}
                    <flux:button wire:click="previousPage" wire:loading.attr="disabled" icon="chevron-left" variant="subtle" size="sm" square />
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)

                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="flex items-center justify-center px-2 text-sm text-zinc-500">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Active Page --}}
                                <flux:button variant="primary" size="sm" square class="cursor-default">
                                    {{ $page }}
                                </flux:button>
                            @else
                                {{-- Inactive Page: Use wire:click="gotoPage" instead of href --}}
                                <flux:button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" variant="subtle" size="sm" square>
                                    {{ $page }}
                                </flux:button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    {{-- Changed href to wire:click --}}
                    <flux:button wire:click="nextPage" wire:loading.attr="disabled" icon="chevron-right" variant="subtle" size="sm" square />
                @else
                    <flux:button disabled icon="chevron-right" variant="subtle" size="sm" square />
                @endif
            </div>
        </div>
    </nav>
@endif
