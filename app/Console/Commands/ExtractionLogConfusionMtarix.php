<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractionLogConfusionMtarix extends Command
{
    protected $signature = 'app:extraction-log-confusion-mtarix';

    protected $description = 'Command description';

    private const NUTRITION_KEYS = [
        'energy', 'total_fat', 'saturated_fat', 'protein', 'total_carbohydrate', 'sugar', 'sodium',
        'vitamin_a', 'vitamin_d', 'vitamin_e', 'vitamin_k', 'vitamin_b1', 'vitamin_b2', 'vitamin_b3',
        'vitamin_b5', 'vitamin_b6', 'folate', 'vitamin_b12', 'biotin', 'choline', 'vitamin_c',
        'calcium', 'phosphorus', 'magnesium', 'potassium', 'manganese', 'copper', 'chromium',
        'iron', 'iodine', 'zinc', 'selenium', 'fluoride'
    ];

    public function handle()
    {
        $basePath = storage_path('app/private/extraction-test-result');

        if (!File::exists($basePath)) {
            $this->error("Directory does not exist: {$basePath}");
            return self::FAILURE;
        }

        $directories = File::directories($basePath);
        $overview = [];

        $globalTP = 0;
        $globalFP = 0;
        $globalFN = 0;
        $globalTN = 0;

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

            $modelUsed = data_get($rawData, 'modelVersion', 'unknown');
            $isValidResponse = data_get($parsedData, 'is_nutrition_label', false) === true;

            if (!$isValidResponse) {
                continue;
            }

            $matrix = $this->calculateConfusionMatrix($truthData, $parsedData);

            $overview[] = [
                'folder_id' => $folderId,
                'model_used' => $modelUsed,
                'matrix' => $matrix
            ];

            $globalTP += $matrix['TP'];
            $globalFP += $matrix['FP'];
            $globalFN += $matrix['FN'];
            $globalTN += $matrix['TN'];

            $validFolders++;
        }

        $overviewPath = "{$basePath}/overview.json";

        $totalPredictions = $globalTP + $globalTN + $globalFP + $globalFN;

        $accuracy = $totalPredictions > 0 ? round(($globalTP + $globalTN) / $totalPredictions, 4) * 100 : 0;
        $precision = ($globalTP + $globalFP) > 0 ? round($globalTP / ($globalTP + $globalFP), 4) * 100 : 0;
        $recall = ($globalTP + $globalFN) > 0 ? round($globalTP / ($globalTP + $globalFN), 4) * 100 : 0;
        $f1Score = ($precision + $recall) > 0 ? round(2 * (($precision * $recall) / ($precision + $recall)), 2) : 0;

        $finalData = [
            'total_valid_tested' => $validFolders,
            'global_confusion_matrix' => [
                'TP' => $globalTP,
                'FP' => $globalFP,
                'FN' => $globalFN,
                'TN' => $globalTN,
            ],
            'metrics' => [
                'accuracy_percentage' => $accuracy,
                'precision_percentage' => $precision,
                'recall_percentage' => $recall,
                'f1_score' => $f1Score
            ],
            'results' => $overview
        ];

        File::put($overviewPath, json_encode($finalData, JSON_PRETTY_PRINT));

        $this->info("Overview generated at {$overviewPath}.");
        $this->line("Global TP: {$globalTP} | FP: {$globalFP} | FN: {$globalFN} | TN: {$globalTN}");
        $this->line("Accuracy: {$accuracy}% | Precision: {$precision}% | Recall: {$recall}% | F1: {$f1Score}");

        return self::SUCCESS;
    }

    private function calculateConfusionMatrix(array $truth, array $parsed): array
    {
        $matrix = [
            'TP' => 0,
            'FP' => 0,
            'FN' => 0,
            'TN' => 0,
        ];

        $truthNutritions = $truth['nutritions'] ?? [];
        $parsedNutritions = $parsed['nutritions'] ?? [];

        foreach (self::NUTRITION_KEYS as $nutrient) {
            $inTruth = isset($truthNutritions[$nutrient]);
            $inParsed = isset($parsedNutritions[$nutrient]);

            if ($inTruth && $inParsed) {
                $isMatch = true;

                foreach ($truthNutritions[$nutrient] as $subKey => $gtVal) {
                    $parsedVal = $parsedNutritions[$nutrient][$subKey] ?? null;

                    if (strcasecmp((string) $gtVal, (string) $parsedVal) !== 0) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    $matrix['TP']++;
                } else {
                    $matrix['FP']++;
                }

            } elseif (!$inTruth && $inParsed) {
                $matrix['FP']++;
            } elseif ($inTruth && !$inParsed) {
                $matrix['FN']++;
            } else {
                $matrix['TN']++;
            }
        }

        foreach ($parsedNutritions as $nutrient => $data) {
            if (!in_array($nutrient, self::NUTRITION_KEYS)) {
                $matrix['FP']++;
            }
        }

        return $matrix;
    }
}
