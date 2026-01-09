@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <flux:button disabled variant="subtle" icon="chevron-left">
                {!! __('pagination.previous') !!}
            </flux:button>
        @else
            <flux:button
                href="{{ $paginator->previousPageUrl() }}"
                variant="subtle"
                icon="chevron-left">
                {!! __('pagination.previous') !!}
            </flux:button>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <flux:button
                href="{{ $paginator->nextPageUrl() }}"
                variant="subtle"
                icon-trailing="chevron-right">
                {!! __('pagination.next') !!}
            </flux:button>
        @else
            <flux:button disabled variant="subtle" icon-trailing="chevron-right">
                {!! __('pagination.next') !!}
            </flux:button>
        @endif

    </nav>
@endif
