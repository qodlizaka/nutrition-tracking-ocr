<?php

namespace App\Actions;

use Illuminate\Support\Facades\Config;
use Exception;

class ExtractNutritionFactsAction
{
    protected string $systemInstruction = "You are a specialized AI assistant for extracting nutritional information from images of Indonesian (BPOM standard) food labels.

    First, analyze the image quality and content:
    1. If the image is too blurry, too dark, or the text is illegible, set 'is_readable' to false.
    2. If the image is NOT a nutrition facts table (e.g., it is a picture of a person, a barcode, or just the front of the packaging), set 'is_nutrition_label' to false.

    If either of those checks fails, set the data fields (serving_size, etc.) to null.

    If the image IS valid:
    1. Set 'is_nutrition_label' and 'is_readable' to true.
    2. Accurately extract all available data.
    3. If the serving per package (sajian per kemasan) is not specified, assume the value is 1.";

    public function __construct(
        protected GeminiRequestAction $geminiRequest
    ) {}

    /**
     * Execute the action.
     *
     * @param string $imageBase64
     * @param string $mimeType
     * @return array
     * @throws Exception
     */
    public function __invoke(string $imageBase64, string $mimeType = 'image/jpeg'): array
    {
        $schema = Config::get('gemini.schemas.nutrition_label');

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    'text' => $this->systemInstruction
                ]
            ],
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Please extract the nutrition facts from this image according to the specified JSON structure."],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $imageBase64
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseJsonSchema' => $schema
            ]
        ];

        [$rawResponse, $parsedResponse] = ($this->geminiRequest)($payload, 'generateContent');

        app(LogGeminiResponse::class)($imageBase64, $payload, $rawResponse, $parsedResponse);

        return $parsedResponse;
    }
}
