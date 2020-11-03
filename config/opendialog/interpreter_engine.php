<?php

return [
    /**
     * Custom interpreters registered in the format
     */
    'custom_interpreters' => [
        \App\Bot\Interpreters\FaqInterpreter::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterWelcome::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterOne::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterTwo::class,
        \App\Bot\Interpreters\MainInterpreters\MainInterpreterThree::class,
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
