<?php


namespace App\Bot\Interpreters;

use OpenDialogAi\Core\Conversation\Intent;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\InterpreterEngine\BaseInterpreter;
use OpenDialogAi\InterpreterEngine\Interpreters\NoMatchIntent;

class FaqInterpreter extends BaseInterpreter
{
    protected static $name = 'interpreter.shoebot.faq';

    public function interpret(UtteranceInterface $utterance): array
    {
        $text = $utterance->getText();

        if ($utterance->getCallbackId() == NoMatchIntent::NO_MATCH) {
            if (str_contains(strtolower($text), 'faq')) {
                $intent = Intent::createIntentWithConfidence('intent.app.dialogflow', 1);
            } else {
                $intent = Intent::createIntentWithConfidence('intent.app.dialogflowNoMatch', 1);
            }
        } else {
            return [];
        }

        return [$intent];
    }
}
