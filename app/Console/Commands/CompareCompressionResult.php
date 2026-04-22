<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CompareCompressionResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:compare-compression-result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare original and compressed image sizes and dimensions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basePath = storage_path('app/private/extraction-test-result');

        if (!File::exists($basePath)) {
            $this->error("The directory {$basePath} does not exist.");
            return;
        }

        $categories = File::directories($basePath);
        $tableData = [];

        foreach ($categories as $categoryPath) {
            $categoryName = basename($categoryPath);
            $subFolders = File::directories($categoryPath);

            foreach ($subFolders as $subFolderPath) {
                $subFolderName = basename($subFolderPath);

                $files = File::files($subFolderPath);

                $compressedFile = null;
                $originalFile = null;

                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    if ($filename === 'image.jpg') {
                        $compressedFile = $file;
                    } elseif (preg_match('/^IMG_\d+\.jpg$/i', $filename)) {
                        $originalFile = $file;
                    }
                }

                if ($compressedFile && $originalFile) {
                    $origSize = $originalFile->getSize();
                    $compSize = $compressedFile->getSize();

                    $origDim = getimagesize($originalFile->getPathname());
                    $compDim = getimagesize($compressedFile->getPathname());

                    $reductionPercentage = 0;
                    if ($origSize > 0) {
                        $reductionPercentage = (($origSize - $compSize) / $origSize) * 100;
                    }

                    $tableData[] = [
                        'Parent Folder' => "{$categoryName}/{$subFolderName}",
                        'Original Image' => $originalFile->getFilename(),
                        'Orig Size' => $this->formatBytes($origSize),
                        'Orig Dim' => $origDim ? "{$origDim[0]}x{$origDim[1]}" : 'N/A',
                        'Comp Size' => $this->formatBytes($compSize),
                        'Comp Dim' => $compDim ? "{$compDim[0]}x{$compDim[1]}" : 'N/A',
                        'Reduction' => number_format($reductionPercentage, 2) . '%',
                    ];
                }
            }
        }

        if (empty($tableData)) {
            $this->info('No matching image pairs found.');
            return;
        }

        $this->table(
            ['Folder Path', 'Original File', 'Orig Size', 'Orig Dimension', 'Compressed Size', 'Comp Dimension', 'Size Reduction'],
            $tableData
        );
    }

    /**
     * Helper to format bytes into readable strings.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
