<?php

return [

    /**
     * Register your application specific actions here. They should be registered with a Fully/Qualified/ClassName
     *
     * Where ClassName is an implementation of @see \OpenDialogAi\ActionEngine\Actions\ActionInterface
     */
    'custom_actions' => [
        \App\Bot\Actions\TrackProgressAction::class,
        \App\Bot\Actions\GetShoesAction::class,
        \App\Bot\Actions\GetSelectionNameAction::class,
        \App\Bot\Actions\ClearRunnerInfoAction::class,
        \App\Bot\Actions\SetFreePronationRequestTrueAction::class,
    ],
];
