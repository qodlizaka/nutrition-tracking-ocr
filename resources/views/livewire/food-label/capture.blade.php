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
            <flux:subheading>{{ __('Take a picture or upload a file to extract nutritional information.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-6" />

    <div
        class="flex flex-col items-center justify-center space-y-6"
        x-data="cameraData($wire)"
        x-init="startCamera()"
    >
        <flux:radio.group wire:model.live="aspectRatio" variant="segmented" size="sm" class="mb-2">
            @foreach(AspectRatio::cases() as $ratio)
                <flux:radio
                    value="{{ $ratio->value }}"
                    icon="{{ $ratio->icon() }}"
                    label="{{ $ratio->label() }}"
                    class="min-w-24"
                    x-on:click="currentRatio = '{{ $ratio->value }}'; setTimeout(() => { if(photo) processFile(lastFileSrc) }, 50)"
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

            <img
                x-show="photo"
                :src="photo"
                class="w-full h-full object-cover"
            />

            <canvas x-ref="canvas" class="hidden"></canvas>

            <div x-show="!hasCamera && !photo" class="absolute inset-0 flex items-center justify-center text-zinc-400 bg-zinc-900">
                <span>{{ __('Waiting for camera or file...') }}</span>
            </div>
        </div>

        <div x-show="isUploading" x-transition.opacity class="w-full max-w-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ __('Uploading image...') }}</span>
                <span class="text-xs text-zinc-500 dark:text-zinc-400 tabular-nums" x-text="Math.round(uploadProgress) + '%'"></span>
            </div>
            <div class="h-2 w-full bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                <div
                    class="h-full bg-zinc-900 dark:bg-white rounded-full transition-all duration-300 ease-out"
                    :style="`width: ${uploadProgress}%`"
                ></div>
            </div>
        </div>

        <div class="flex flex-col items-center gap-4 w-full max-w-xl">

            <div x-show="!photo" class="flex flex-col sm:flex-row gap-4 w-full justify-center">

                <div class="w-full sm:w-auto">
                    <flux:input
                        type="file"
                        accept="image/*"
                        x-on:change="handleFileSelect($event)"
                        placeholder="Upload from gallery"
                    />
                </div>

                <div x-show="hasCamera" class="w-full sm:w-auto">
                    <flux:button variant="primary" class="w-full" x-on:click="capture" icon="camera">
                        {{ __('Take Photo') }}
                    </flux:button>
                </div>
            </div>

            <div x-show="photo" class="flex gap-4 w-full justify-center">
                <flux:button variant="subtle" x-on:click="retake" x-bind:disabled="isUploading">
                    {{ __('Retake') }}
                </flux:button>

                <flux:button variant="primary" x-on:click="save" icon="check" x-bind:disabled="isUploading">
                    <span x-text="isUploading ? 'Processing...' : '{{ __('Use this Photo') }}'"></span>
                </flux:button>
            </div>

            <flux:error name="photoUpload" />
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cameraData', (wire) => ({
                stream: null,
                photo: null,
                lastFileSrc: null,
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
                            video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } }
                        });
                        this.$refs.video.srcObject = this.stream;
                        this.hasCamera = true;
                    } catch (error) {
                        console.warn('Camera access denied or unavailable', error);
                        this.hasCamera = false;
                    }
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.lastFileSrc = e.target.result;
                        this.processFile(this.lastFileSrc);
                    };
                    reader.readAsDataURL(file);
                },

                processFile(imageSrc) {
                    const img = new Image();
                    img.onload = () => {
                        this.drawToCanvas(img, img.width, img.height);
                    };
                    img.src = imageSrc;
                },

                capture() {
                    const video = this.$refs.video;
                    if (video.videoWidth === 0) return;
                    this.drawToCanvas(video, video.videoWidth, video.videoHeight);
                },

                drawToCanvas(source, sourceW, sourceH) {
                    const canvas = this.$refs.canvas;
                    const ctx = canvas.getContext('2d');
                    const sourceRatio = sourceW / sourceH;
                    const targetRatio = this.ratios[this.currentRatio];

                    let cropW, cropH, cropX, cropY;

                    if (sourceRatio > targetRatio) {
                        cropH = sourceH;
                        cropW = sourceH * targetRatio;
                        cropX = (sourceW - cropW) / 2;
                        cropY = 0;
                    } else {
                        cropW = sourceW;
                        cropH = sourceW / targetRatio;
                        cropX = 0;
                        cropY = (sourceH - cropH) / 2;
                    }

                    canvas.width = cropW;
                    canvas.height = cropH;

                    ctx.drawImage(
                        source,
                        cropX, cropY, cropW, cropH,
                        0, 0, cropW, cropH
                    );

                    this.photo = canvas.toDataURL('image/jpeg', 1);
                },

                retake() {
                    this.photo = null;
                    this.lastFileSrc = null;
                    const fileInput = document.querySelector('input[type="file"]');
                    if(fileInput) fileInput.value = '';
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
                                        wire.extractNutritionLabel()
                                            .then(() => {
                                                this.isUploading = false;
                                            })
                                            .catch((error) => {
                                                console.error(error);
                                                this.isUploading = false;
                                                alert('An error occurred during processing.');
                                            });
                                    }, 300);
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
            }))
        })
    </script>
</div>
