<?php

namespace App\Actions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiRequestAction
{
    /**
     * Execute the Gemini API request with failover support.
     *
     * @param array $payload The request body.
     * @param string $endpointType The action type (e.g., 'generateContent', 'countTokens').
     * @return array|int The parsed response data.
     * @throws Exception
     */
    public function __invoke(array $payload, string $endpointType = 'generateContent')
    {
        $apiKey = Config::get('gemini.api_key');
        $models = Config::get('gemini.models');

        if (!$apiKey) {
            throw new Exception("Gemini API Key is missing.");
        }

        foreach ($models as $key => $modelConfig) {
            try {
                $url = "{$modelConfig['url']}:{$endpointType}";

                $response = Http::acceptJson()
                    ->withQueryParameters(['key' => $apiKey])
                    ->post($url, $payload);

                if ($response->ok()) {
                    return $this->parseResponse($response->json(), $endpointType);
                }

                Log::warning("Gemini Model Failed ({$endpointType}): {$key}", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

            } catch (\Exception $e) {
                Log::error("Gemini Connection Error ({$endpointType}): {$key}", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        throw new Exception("All Gemini models failed to process the {$endpointType} request.");
    }

    /**
     * Parse the response based on the endpoint type.
     */
    protected function parseResponse(array $data, string $endpointType)
    {
        if ($endpointType === 'countTokens') {
            return $data['totalTokens'];
        }

        $responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

        $responseText = $this->cleanJsonString($responseText);

        return json_decode($responseText, true);
    }

    /**
     * Helper to remove markdown formatting if present.
     */
    private function cleanJsonString(string $text): string
    {
        $text = preg_replace('/^```json\s*/', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        return $text;
    }
}
