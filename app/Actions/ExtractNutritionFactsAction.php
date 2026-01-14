<?php

namespace App\Actions;

use Illuminate\Support\Facades\Config;
use Exception;

class ExtractNutritionFactsAction
{
    protected string $systemInstruction = "You are a specialized AI assistant for extracting nutritional information from images of Indonesian (BPOM standard) food labels. Your task is to accurately extract all available data and format it into the provided JSON schema. In the final JSON, only include the keys for nutrients that are explicitly present on the label. If a nutrient is not listed, omit its key. If the image provided is not a nutrition facts table, do not return any data. If the serving per package (sajian per kemasan) is not specified, assume the value is 1.";

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

        return ($this->geminiRequest)($payload, 'generateContent');
    }
}
