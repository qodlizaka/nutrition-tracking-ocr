<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden rounded-xl border border-zinc-200 bg-white dark:bg-zinc-900 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead>
            <tr class="bg-zinc-50/50 dark:bg-zinc-800/50">
                {{ $header }}
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
            {{ $slot }}
        </tbody>
    </table>
</div>
