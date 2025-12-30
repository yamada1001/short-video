<?php
/**
 * API設定（OpenAI, Anthropic）
 */

return [
    'use_mock' => getenv('USE_MOCK_API') === 'true',

    'openai' => [
        'api_key' => getenv('OPENAI_API_KEY'),
        'endpoint' => 'https://api.openai.com/v1/chat/completions',
        'models' => [
            'gpt-4' => 'gpt-4',
            'gpt-4-turbo' => 'gpt-4-turbo',
            'gpt-3.5-turbo' => 'gpt-3.5-turbo',
        ],
        'default_model' => 'gpt-4-turbo',
        'timeout' => 120, // 秒
    ],

    'anthropic' => [
        'api_key' => getenv('ANTHROPIC_API_KEY'),
        'endpoint' => 'https://api.anthropic.com/v1/messages',
        'model' => 'claude-3-7-sonnet-20250219',
        'version' => '2023-06-01',
        'timeout' => 120,
    ],
];
