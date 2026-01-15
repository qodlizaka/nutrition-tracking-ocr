@use('App\Enum\AspectRatio')

<div class="w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" />
        <flux:breadcrumbs.item>{{ __('Food label') }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('food.label.capture') }}">{{ __('Capture') }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
        <div>
            <flux:heading size="xl">{{ __('Capture food label') }}</flux:heading>
            <flux:subheading>{{ __('Take a picture of a food label to automatically extract its nutritional information.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div
        class="flex flex-col items-center justify-center space-y-6"
        x-data="cameraData($wire)"
        x-init="startCamera()"
    >
        <flux:radio.group wire:model.live="aspectRatio" variant="segmented" size="sm" class="mb-6">
            @foreach(AspectRatio::cases() as $ratio)
                <flux:radio
                    value="{{ $ratio->value }}"
                    icon="{{ $ratio->icon() }}"
                    label="{{ $ratio->label() }}"
                    class="min-w-24"
                />
            @endforeach
        </flux:radio.group>

        <div
            class="relative w-full max-w-xl bg-black rounded-xl overflow-hidden shadow-lg border border-zinc-200 dark:border-zinc-700 transition-all duration-300 ease-in-out group"
            :class="classes[currentRatio]"
        >
            <video
                x-ref="video"
                x-show="!photo"
                autoplay
                playsinline
                class="w-full h-full object-cover"
            ></video>

            {{-- VIEWFINDER OVERLAY START --}}
            <div x-show="!photo && hasCamera" class="absolute inset-0 pointer-events-none p-4 sm:p-6 z-10 flex flex-col justify-between">
                <div class="flex justify-between w-full">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 border-t-4 border-l-4 border-white/80 rounded-tl-lg drop-shadow-md"></div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 border-t-4 border-r-4 border-white/80 rounded-tr-lg drop-shadow-md"></div>
                </div>

                <div class="absolute inset-0 flex items-center justify-center opacity-30">
                     <div class="w-12 h-[2px] bg-white"></div>
                     <div class="h-12 w-[2px] bg-white absolute"></div>
                </div>

                <div class="flex justify-between w-full">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 border-b-4 border-l-4 border-white/80 rounded-bl-lg drop-shadow-md"></div>
                    <div class="w-8 h-8 sm:w-12 sm:h-12 border-b-4 border-r-4 border-white/80 rounded-br-lg drop-shadow-md"></div>
                </div>
            </div>
            {{-- VIEWFINDER OVERLAY END --}}

            <img
                x-show="photo"
                :src="photo"
                class="w-full h-full object-cover"
            />

            <canvas x-ref="canvas" class="hidden"></canvas>

            <div x-show="!hasCamera && !photo" class="absolute inset-0 flex items-center justify-center text-zinc-400">
                <span>{{ __('Requesting Camera Access...') }}</span>
            </div>
        </div>

        <div class="flex gap-4">
            <div x-show="!photo && hasCamera">
                <flux:button variant="primary" x-on:click="capture" icon="camera">
                    {{ __('Capture Photo') }}
                </flux:button>
            </div>

            <div x-show="photo" class="flex gap-4">
                <flux:button variant="subtle" x-on:click="retake" x-bind:disabled="isUploading">
                    {{ __('Retake') }}
                </flux:button>

                <flux:button variant="primary" x-on:click="save" icon="check" x-bind:disabled="isUploading">
                    <span x-text="isUploading ? 'Uploading...' : '{{ __('Use this Photo') }}'"></span>
                </flux:button>
            </div>
        </div>

        <div
            x-show="isUploading"
            x-transition
            class="w-full max-w-xl mt-4 px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 flex flex-col gap-2"
        >
            <div class="flex items-center gap-3">
                <div class="shrink-0 text-zinc-500">
                    <flux:icon icon="arrow-path" variant="mini" class="animate-spin w-5 h-5" />
                </div>

                <div class="w-full">
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ __('Analyzing Label') }}</span>
                        <span class="text-xs font-mono text-zinc-500" x-text="Math.round(uploadProgress) + '%'"></span>
                    </div>

                    <div class="h-1.5 w-full bg-zinc-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-indigo-600 dark:bg-indigo-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_10px_rgba(79,70,229,0.3)]"
                            :style="`width: ${uploadProgress}%`"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cameraData', (wire) => (
                {
                    stream: null,
                    photo: null,
                    hasCamera: false,
                    currentRatio: @entangle('aspectRatio').live,

                    uploadProgress: 0,
                    isUploading: false,

                    ratios: {
                        @foreach(AspectRatio::cases() as $case)
                            '{{ $case->value }}': {{ $case->getMultiplier() }},
                        @endforeach
                    },

                    classes: {
                        @foreach(AspectRatio::cases() as $case)
                            '{{ $case->value }}': '{{ $case->getCssClass() }}',
                        @endforeach
                    },

                    async startCamera() {
                        try {
                            this.stream = await navigator.mediaDevices.getUserMedia({
                                video: {
                                    facingMode: 'environment',
                                    width: { ideal: 1920 },
                                    height: { ideal: 1080 }
                                }
                            });
                            this.$refs.video.srcObject = this.stream;
                            this.hasCamera = true;
                        } catch (error) {
                            console.error('Error accessing camera:', error);
                            alert('Unable to access camera. Please allow permissions.');
                        }
                    },

                    capture() {
                        const canvas = this.$refs.canvas;
                        const video = this.$refs.video;
                        const ctx = canvas.getContext('2d');

                        const videoW = video.videoWidth;
                        const videoH = video.videoHeight;

                        if (videoW === 0 || videoH === 0) return;

                        const videoRatio = videoW / videoH;

                        const targetRatio = this.ratios[this.currentRatio];

                        let cropW, cropH, cropX, cropY;

                        if (videoRatio > targetRatio) {
                            cropH = videoH;
                            cropW = videoH * targetRatio;
                            cropX = (videoW - cropW) / 2;
                            cropY = 0;
                        } else {
                            cropW = videoW;
                            cropH = videoW / targetRatio;
                            cropX = 0;
                            cropY = (videoH - cropH) / 2;
                        }

                        canvas.width = cropW;
                        canvas.height = cropH;

                        ctx.drawImage(
                            video,
                            cropX, cropY, cropW, cropH,
                            0, 0, cropW, cropH
                        );

                        this.photo = canvas.toDataURL('image/jpeg', 0.85);
                    },

                    retake() {
                        this.photo = null;
                    },

                    save() {
                        if (!this.photo) return;

                        this.isUploading = true;
                        this.uploadProgress = 1;

                        fetch(this.photo)
                            .then(res => res.blob())
                            .then(blob => {
                                const file = new File([blob], "capture.jpg", { type: "image/jpeg" });

                                @this.upload(
                                    'photoUpload',
                                    file,
                                    (uploadedFilename) => {
                                        this.uploadProgress = 100;

                                        setTimeout(() => {
                                            wire.extractNutritionLabel();
                                        }, 500);
                                    },
                                    () => {
                                        this.isUploading = false;
                                        this.uploadProgress = 0;
                                        alert('Upload failed. Please try again.');
                                    },
                                    (event) => {
                                        this.uploadProgress = event.detail.progress;
                                    }
                                );
                            });
                    }
                }
            ))
        })
    </script>
</div>
