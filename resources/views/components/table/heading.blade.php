@props([
    'sortable' => null,
    'direction' => null,
])

<th
    {{ $attributes->merge(['class' => 'px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider whitespace-nowrap' . ($sortable ? ' cursor-pointer group hover:bg-zinc-50' : '')]) }}
>
    @if($sortable)
        <div class="flex items-center gap-1">
            <span>{{ $slot }}</span>

            <span class="relative flex items-center">
                @if($direction === 'asc')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-zinc-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                @elseif($direction === 'desc')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-zinc-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-zinc-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                @endif
            </span>
        </div>
    @else
        {{ $slot }}
    @endif
</th>
