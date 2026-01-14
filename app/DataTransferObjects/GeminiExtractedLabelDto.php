<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GeminiExtractedLabelDto
{
    /**
     * Create a new class instance.
     * "serving_size" => 200,
     * "serving_unit" => "ml",
     * "serving_per_package" => 5,
     * "nutrition" => [
     */
    public function __construct(
        public int $servingSize,
        public string $servingUnit,
        public int $servingPerPackage,
        public Collection $nutritions
    )
    {
    }

    public static function fromApiResult(array $result): GeminiExtractedLabelDto
    {
        return new self(
            $result['serving_size'],
            $result['serving_unit'],
            $result['serving_per_package'],
            collect($result['nutritions'])
                ->map(fn($nutri, $name) => [
                    'name' => Str::replace('_', ' ', $name),
                    ...$nutri,
                ])
                ->values(),
        );
    }
}
