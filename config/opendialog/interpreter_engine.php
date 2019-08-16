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
        'Surface' => 'surface',
        'builtin.personName' => 'first_name',
        'builtin.email' => 'email',
        'builtin.phonenumber' => 'contact_number',
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
        'start_discovery' => 'intent.shoebot.start_shoe_discovery',
        'test_intent' => 'intent.shoebot.test_intent',
        'user_not_ready' => 'intent.shoebot.user_not_ready',
        'book_call' => 'intent.shoebot.book_call',
        'no_call' => 'intent.shoebot.no_call',
        'gender' => 'intent.shoebot.gender',
        'experience_incorrect' => 'intent.shoebot.experience_incorrect',
        'move_on' => 'intent.shoebot.move_on',
        'send_distance' => 'intent.shoebot.send_distance',
        'send_terrain' => 'intent.shoebot.send_terrain',
        'send_freq' => 'intent.shoebot.send_freq',
        'info_continue' => 'intent.shoebot.info_continue',
        'send_sturdy' => 'intent.shoebot.send_sturdy',
        'send_gender' => 'intent.shoebot.send_gender',
        'send_pronation' => 'intent.shoebot.start_recommendations',
        'work_out_pronation' => 'intent.shoebot.work_out_pronation',
        'describe_process' => 'intent.shoebot.describe_process',
        'not_now_pronation' => 'intent.shoebot.not_now_pronation',
        'recommendations' => 'intent.shoebot.start_recommendations',
        'sign_up_for_updates' => 'intent.shoebot.sign_up_for_updates',
        'dont_sign_up_for_updates' => 'intent.shoebot.dont_sign_up_for_updates',
        'refresh' => 'intent.shoebot.refresh',
    ]
];
