<?php


namespace App\Bot\Interpreters;

use OpenDialogAi\ContextEngine\Facades\AttributeResolver;
use OpenDialogAi\Core\Conversation\Intent;
use OpenDialogAi\Core\Utterances\UtteranceInterface;

class FeedbackInterpreter extends AbstractDialogflowInterpreter
{
    protected static $name = 'interpreter.shoebot.feedback';

    public function interpret(UtteranceInterface $utterance): array
    {
        $text = $utterance->getText();

        $intent = Intent::createIntentWithConfidence('intent.shoebot.feedback', 1);
        $intent->addAttribute(AttributeResolver::getAttributeFor('survey_answer', $text));

        return [$intent];
    }
}
