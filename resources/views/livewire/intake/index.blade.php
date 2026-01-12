<x-table>
    <x-slot name="header">
        <x-table.heading>Customer</x-table.heading>

        <x-table.heading sortable wire:click="sort('date')" :direction="$sortBy === 'date' ? $sortDirection : null">
            Date
        </x-table.heading>

        <x-table.heading sortable wire:click="sort('status')" :direction="$sortBy === 'status' ? $sortDirection : null">
            Status
        </x-table.heading>

        <x-table.heading sortable wire:click="sort('amount')" :direction="$sortBy === 'amount' ? $sortDirection : null">
            Amount
        </x-table.heading>

        <x-table.heading /> </x-slot>

    @foreach ($this->orders as $order)
        <x-table.row wire:key="{{ $order->id }}">
            <x-table.cell class="flex items-center gap-3">
                <img src="{{ $order->customer_avatar }}" class="w-6 h-6 rounded-full bg-zinc-200" alt="">
                <span class="font-medium text-zinc-900">{{ $order->customer }}</span>
            </x-table.cell>

            <x-table.cell class="whitespace-nowrap text-zinc-500">
                {{ $order->date }}
            </x-table.cell>

            <x-table.cell>
                <span @class([
                    'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ring-1 ring-inset',
                    'bg-green-50 text-green-700 ring-green-600/20' => $order->status === 'Paid',
                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20' => $order->status === 'Pending',
                    'bg-red-50 text-red-700 ring-red-600/20' => $order->status === 'Failed',
                ])>
                    {{ $order->status }}
                </span>
            </x-table.cell>

            <x-table.cell class="font-medium text-zinc-900">
                {{ $order->amount }}
            </x-table.cell>

            <x-table.cell class="text-right">
                <button class="text-zinc-400 hover:text-zinc-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                    </svg>
                </button>
            </x-table.cell>
        </x-table.row>
    @endforeach
</x-table>

<div class="mt-4">
    {{ $this->orders->links() }}
</div>
