<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractionLogResult extends Command
{
    protected $signature = 'extraction-log:result';
    protected $description = 'Iterate through all gemini extraction test results and calculate accuracy.';

    public function handle()
    {
        $basePath = storage_path('app/private/extraction-test-result');

        if (!File::exists($basePath)) {
            $this->error("Directory does not exist: {$basePath}");
            return self::FAILURE;
        }

        $directories = File::directories($basePath);
        $overview = [];
        $totalAccuracy = 0;
        $validFolders = 0;

        foreach ($directories as $dir) {
            $folderId = basename($dir);

            $parsedPath = "{$dir}/gemini-api/parsed_response.json";
            $rawPath = "{$dir}/gemini-api/raw_response.json";
            $truthPath = "{$dir}/ground_truth.json";

            if (!File::exists($truthPath) || !File::exists($parsedPath) || !File::exists($rawPath)) {
                $this->warn("Missing required JSON files in folder: {$folderId}, skipping.");
                continue;
            }

            $parsedData = json_decode(File::get($parsedPath), true) ?? [];
            $rawData = json_decode(File::get($rawPath), true) ?? [];
            $truthData = json_decode(File::get($truthPath), true) ?? [];

            $accuracy = $this->calculateAccuracy($truthData, $parsedData);

            $modelUsed = data_get($rawData, 'modelVersion', 'unknown');
            $isValidResponse = data_get($parsedData, 'is_nutrition_label', false) === true;

            $overview[] = [
                'folder_id' => $folderId,
                'is_valid_response' => $isValidResponse,
                'model_used' => $modelUsed,
                'accuracy_percentage' => $accuracy,
            ];

            $totalAccuracy += $accuracy;
            $validFolders++;
        }

        $overviewPath = "{$basePath}/overview.json";

        $finalData = [
            'global_accuracy' => $validFolders > 0 ? round($totalAccuracy / $validFolders, 2) : 0,
            'total_tested' => $validFolders,
            'results' => $overview
        ];

        File::put($overviewPath, json_encode($finalData, JSON_PRETTY_PRINT));

        $this->info("Overview generated at {$overviewPath}. Global Accuracy: {$finalData['global_accuracy']}%");
        return self::SUCCESS;
    }

    private function calculateAccuracy(array $truth, array $parsed): float
    {
        $expectedPoints = 0;
        $matchedPoints = 0;

        $topLevelKeys = ['is_nutrition_label', 'is_readable', 'serving_size', 'serving_unit', 'serving_per_package'];
        foreach ($topLevelKeys as $key) {
            if (\array_key_exists($key, $truth)) {
                $expectedPoints++;

                $truthValue = $truth[$key] ?? null;
                $parsedValue = $parsed[$key] ?? null;

                if (\is_string($truthValue) && \is_string($parsedValue)) {
                    if (strtolower($truthValue) === strtolower($parsedValue)) $matchedPoints++;
                } else {
                    if ($truthValue === $parsedValue) $matchedPoints++;
                }
            }
        }

        if (isset($truth['is_nutrition_label']) && $truth['is_nutrition_label'] === false) {
            return $expectedPoints > 0 ? round(($matchedPoints / $expectedPoints) * 100, 2) : 0;
        }

        $truthNutritions = $truth['nutritions'] ?? [];
        $parsedNutritions = $parsed['nutritions'] ?? [];

        foreach ($truthNutritions as $nutrient => $data) {
            foreach ($data as $subKey => $truthValue) {
                $expectedPoints++;

                $parsedValue = $parsedNutritions[$nutrient][$subKey] ?? null;

                if (\is_string($truthValue) && \is_string($parsedValue)) {
                     if (strtolower($truthValue) === strtolower($parsedValue)) $matchedPoints++;
                } else {
                     if ($truthValue === $parsedValue) $matchedPoints++;
                }
            }
        }

        foreach ($parsedNutritions as $nutrient => $data) {
            foreach ($data as $subKey => $parsedValue) {
                if (!isset($truthNutritions[$nutrient][$subKey])) {
                    $expectedPoints++;
                }
            }
        }

        return $expectedPoints > 0 ? round(($matchedPoints / $expectedPoints) * 100, 2) : 0;
    }
}
