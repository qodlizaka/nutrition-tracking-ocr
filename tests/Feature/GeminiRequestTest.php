<?php

use App\Actions\GeminiRequestAction;

test('can_count_token', function () {
    $payload = [
        "contents" => [
            "parts" => [
                "text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum"
            ],
        ],
    ];

    $tokenCount = (new GeminiRequestAction())($payload, 'countTokens');

    $this->assertEquals(83, $tokenCount);
});
