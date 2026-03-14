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
        'nutrition_label' => [
            'type' => 'object',
            'required' => [
                'is_nutrition_label',
                'is_readable',
            ],
            'properties' => [
                'is_nutrition_label' => [
                    'type' => 'boolean',
                    'description' => 'Set to true ONLY if the image clearly contains a nutrition facts table (Informasi Nilai Gizi).',
                ],
                'is_readable' => [
                    'type' => 'boolean',
                    'description' => 'Set to false if the text is too blurry, too dark, or cut off to be read accurately.',
                ],
                'serving_size' => [
                    'type' => 'number',
                    'description' => 'The size of one serving (Takaran Saji). Return null if not a label.',
                ],
                'serving_unit' => [
                    'type' => 'string',
                    'description' => 'The unit for the serving size. Return null if not a label.',
                ],
                'serving_per_package' => [
                    'type' => 'number',
                    'description' => 'Sajian per Kemasan. Return null if not a label.',
                ],
                'nutritions' => [
                    'type' => 'object',
                    'description' => 'Object containing all nutrient values.',
                    'properties' => [
                        'energy' => [
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
                        'sodium' => [
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
        'sample' => [
            "is_nutrition_label" => true,
            "is_readable" => true,
            "serving_size" => 200,
            "serving_unit" => "ml",
            "serving_per_package" => 5,
            "nutritions" => [
                "energy" => [
                    "value" => 180,
                ],
                "total_fat" => [
                    "value" => 7,
                    "unit" => "g",
                ],
                "saturated_fat" => [
                    "value" => 4,
                    "unit" => "g",
                ],
                "protein" => [
                    "value" => 6,
                    "unit" => "g",
                ],
                "total_carbohydrate" => [
                    "value" => 24,
                    "unit" => "g",
                ],
                "sugar" => [
                    "value" => 16,
                    "unit" => "g",
                ],
                "sodium" => [
                    "value" => 95,
                    "unit" => "mg",
                    "percentage" => 6,
                ],
                "vitamin_d" => [
                    "percentage" => 35,
                ],
                "vitamin_e" => [
                    "percentage" => 20,
                ],
                "vitamin_b1" => [
                    "percentage" => 35,
                ],
                "vitamin_b2" => [
                    "percentage" => 25,
                ],
                "calcium" => [
                    "percentage" => 30,
                ],
                "phosphorus" => [
                    "percentage" => 25,
                ],
                "magnesium" => [
                    "percentage" => 10,
                ],
                "potassium" => [
                    "percentage" => 8,
                ],
                "iron" => [
                    "percentage" => 10,
                ],
                "zinc" => [
                    "percentage" => 10,
                ],
                "selenium" => [
                    "percentage" => 50,
                ],
            ],
        ],
    ],
];
