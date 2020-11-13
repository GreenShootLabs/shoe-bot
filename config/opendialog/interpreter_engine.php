<?php

return [
    'dialogflow_config' => [
        'faq' => [
            'project_id' => env('DIALOGFLOW_FAQ_PROJECT_ID')
        ],
        'main' => [
            'project_id' => env('DIALOGFLOW_MAIN_PROJECT_ID')
        ],
        'credentials' => [
            '_fallback' => env('DIALOGFLOW_FALLBACK_CREDENTIALS_PATH')
        ]
    ],

    /**
     * Custom interpreters registered in the format
     */
    'custom_interpreters' => [
        \App\Bot\Interpreters\FaqInterpreter::class,
        \App\Bot\Interpreters\FeedbackInterpreter::class,
        \App\Bot\Interpreters\MainInterpreter::class,
    ],

    'default_interpreter' => 'interpreter.core.callbackInterpreter',

    /**
     * List of supported intents in the format 'callback_id' => 'intent_name'
     */
    'supported_callbacks' => [
        'WELCOME' => 'intent.core.welcome',
        'shoe_size' => 'intent.shoebot.shoeSize'
    ]
];
