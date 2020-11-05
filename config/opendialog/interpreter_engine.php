<?php

return [
    'default_cache_time' => 0,

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
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterWelcome::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterOne::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterTwo::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterThree::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterFour::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterFive::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterSix::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterSeven::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterEight::class,
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
