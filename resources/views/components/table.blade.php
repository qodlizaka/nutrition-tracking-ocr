<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden rounded-xl border border-zinc-200 bg-white">
    <table class="min-w-full divide-y divide-zinc-200">
        <thead>
            <tr class="bg-zinc-50/50">
                {{ $header }}
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 bg-white">
            {{ $slot }}
        </tbody>
    </table>
</div>
