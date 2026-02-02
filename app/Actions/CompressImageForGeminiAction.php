<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Interfaces\ImageInterface;

class CompressImageForGeminiAction
{
    /**
     * Compress and encode an image for Gemini API consumption.
     *
     * @param mixed $imageInput File path, UploadedFile, or binary data.
     * @return string The raw Base64 encoded string (no data-uri prefix).
     */
    public function __invoke(UploadedFile|string $file): string
    {
        $image = Image::read($file);

        $image->scaleDown(width: 1024, height: 1024);

        $encoded = $image->toJpeg(quality: 85);

        return base64_encode((string) $encoded);
    }
}
