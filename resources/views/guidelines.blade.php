<x-layouts.front title="{{ __( 'Guidelines') }}">
    <div class="prose lg:prose-lg dark:prose-invert">
        {!! $content !!}

        <flux:button variant="primary" href="/" class="no-underline!">{{ __('Back to Welcome') }}</flux:button>
    </div>
</x-layouts.front>
