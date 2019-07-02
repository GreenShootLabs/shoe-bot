<?php

return [
    /**
     * A registration of know LUIS entities mapped to known attribute type. If an entity is returned from LUIS that is
     * not an already registered attribute name and is not mapped here, a StringAttribute will be used
     *
     * Mapping is {luis_entity_type} => {OD_attribute_name}
     */
    'luis_entities' => [
        'ExperienceLevel' => 'experience',
        'Distance' => 'distance',
        'LengthOfTime' => 'length',
        'Surface' => 'surface'
    ],

    /**
     * Custom interpreters registered in the format
     */
    'custom_interpreters' => [
        \OpenDialogAi\InterpreterEngine\Interpreters\LuisInterpreter::class,
        \App\Bot\Interpreters\DefaultInputInterpreter::class
    ],

    'default_interpreter' => 'interpreter.core.callbackInterpreter',

    /**
     * List of supported intents in the format 'callback_id' => 'intent_name'
     */
    'supported_callbacks' => [
        'WELCOME' => 'intent.core.welcome',
        'list_message' => 'intent.core.TestListMessage',
        'start_discovery' => 'intent.shoebot.startShoeDiscovery',
        'end_conversation' => 'intent.shoebot.endConversation',
        'book_call' => 'intent.shoebot.bookCall',
        'no_call' => 'intent.shoebot.noCall',
        'continue' => 'intent.shoebot.continue',
        'distance' => 'intent.shoebot.distance',
        'surface' => 'intent.shoebot.surface',
        'frequency' => 'intent.shoebot.frequency',
        'experience' => 'intent.shoebot.experience',
        'sturdiness' => 'intent.shoebot.sturdiness',
        'foot_type' => 'intent.shoebot.footType',
        'gender' => 'intent.shoebot.gender',
        'yes_updates' => 'intent.shoebot.getUpdates',
        'no_updates' => 'intent.shoebot.noUpdates',
        'pronation_help' => 'intent.shoebot.pronationHelp',
        'pronation_continue' => 'intent.shoebot.pronationHelpContinue',
        'pronation_end' => 'intent.shoebot.pronationHelpEnd',
        'buy_now' => 'intent.shoebot.buyNow',
        'experience_correct' => 'intent.shoebot.experienceCorrect',
        'experience_incorrect' => 'intent.shoebot.experienceIncorrect',
        'move_on' => 'intent.shoebot.moveOn'
    ]
];