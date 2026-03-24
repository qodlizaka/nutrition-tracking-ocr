<?php

namespace App\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogGeminiResponse
{
    /**
     * Invoke the class instance.
     */
    public function __invoke(string $base64Image, array $payload, array $rawResponse, array $parsedResponse): void
    {
        $folder_name = (string) Str::uuid();

        $base_path = 'extraction-test-result/' . $folder_name;

        Storage::disk('local')->makeDirectory($base_path);

        $clean_base_64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
        $decodedImage = base64_decode($clean_base_64);

        Storage::disk('local')->put($base_path . '/image.jpg', $decodedImage);

        $gemini_path = $base_path . '/gemini-api';
        Storage::disk('local')->makeDirectory($gemini_path);

        Storage::disk('local')->put($base_path . '/ground_truth.json', '');
        Storage::disk('local')->put($gemini_path . '/payload.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Storage::disk('local')->put($gemini_path . '/raw_response.json', json_encode($rawResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Storage::disk('local')->put($gemini_path . '/parsed_response.json', json_encode($parsedResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        Log::info('Success storing Gemini response');
    }
}
