<?php

return [
    'api_key' => env('GEMINI_API_KEY'),
    'models' => [
        'gemini-3-flash' => [
            'url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview',
        ],
        'gemini-2.5-flash' => [
            'url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash',
        ],
        'gemini-2.5-flash-lite' => [
            'url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite',
        ],
    ],
    'schemas' => [
        'type' => 'object',
        'required' => [
            'serving_size',
            'serving_unit',
            'nutrition',
        ],
        'properties' => [
            'serving_size' => [
                'type' => 'number',
                'description' => 'The size of one serving (Takaran Saji).',
            ],
            'serving_unit' => [
                'type' => 'string',
                'description' => 'The unit for the serving size, e.g., g, ml, keping.',
            ],
            'serving_per_package' => [
                'type' => 'number',
                'description' => 'The number of servings in the package (Sajian per Kemasan).',
            ],
            'nutrition' => [
                'type' => 'object',
                'description' => 'Object containing all nutrient values.',
                'properties' => [
                    'total_energy' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                        ],
                    ],
                    'total_fat' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'saturated_fat' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'protein' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'total_carbohydrate' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'sugar' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'salt_sodium' => [
                        'type' => 'object',
                        'properties' => [
                            'value' => ['type' => 'number'],
                            'unit' => ['type' => 'string'],
                        ],
                    ],
                    'vitamin_a' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_d' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_e' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_k' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b1' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b2' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b3' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b5' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b6' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'folate' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_b12' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'biotin' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'choline' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'vitamin_c' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'calcium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'phosphorus' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'magnesium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'sodium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'potassium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'manganese' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'copper' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'chromium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'iron' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'iodine' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'zinc' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'selenium' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                    'fluoride' => [
                        'type' => 'object',
                        'properties' => ['percentage' => ['type' => 'number']],
                    ],
                ],
            ],
        ],
    ],
];
