<x-layouts.front title="{{ __('Welcome') }}">

    <main class="flex w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

        <div class="flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none">

            <flux:heading size="lg" class="mb-4">
                <flux:icon.scan-text class="mb-2" />
                {{ __('Nutrition Tracking OCR') }}
            </flux:heading>

            <flux:subheading class="mb-6 !text-[#706f6c] dark:!text-[#A1A09A]">
                {{ __("Effortlessly track your nutritional intake by scanning food labels with our AI OCR technology.") }}
            </flux:subheading>

            <ul class="flex flex-col mb-6 lg:mb-8">
                <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                    <span class="relative py-1 bg-white dark:bg-[#161615]">
                        <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-5 h-5 border dark:border-[#3E3E3A] border-[#e3e3e0] text-[#dbdbd7] dark:text-[#3E3E3A]">
                            <flux:icon.book-open variant="micro" class="text-zinc-400 dark:text-zinc-500" />
                        </span>
                    </span>
                    <flux:text>
                        {{ __('Read our') }}
                        <flux:link href="{{ route('guidelines') }}" variant="primary" class="font-medium !text-[#f53003] dark:!text-[#FF4433]">
                            {{ __('Guidelines') }}
                        </flux:link>
                    </flux:text>
                </li>

                <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1/2 before:top-0 before:start-[0.4rem] before:absolute">
                    <span class="relative py-1 bg-white dark:bg-[#161615]">
                        <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-5 h-5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                            <flux:icon.play-circle variant="micro" class="text-zinc-400 dark:text-zinc-500" />
                        </span>
                    </span>
                    <flux:text>
                        {{ __('Watch the') }}
                        <flux:link href="{{ config('app.youtube_tutorial_url') }}" target="_blank" variant="primary" class="font-medium !text-[#f53003] dark:!text-[#FF4433]">
                            {{ __('Tutorial') }}
                        </flux:link>
                        <flux:icon.arrow-up-right variant="micro" class="inline" />
                    </flux:text>
                </li>
            </ul>

            <div class="flex gap-3">
                <flux:button href="{{ route('login') }}" variant="filled" class="!bg-[#1b1b18] !text-white hover:!bg-black dark:!bg-[#eeeeec] dark:!text-[#1C1C1A] dark:hover:!bg-white border !border-black dark:!border-[#eeeeec]">
                    {{ __('Get Started') }}!
                </flux:button>
            </div>
        </div>

        <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ms-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg! aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
            <img src="{{ asset('images/welcome.jpg') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
        </div>
    </main>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</x-layouts.front>
