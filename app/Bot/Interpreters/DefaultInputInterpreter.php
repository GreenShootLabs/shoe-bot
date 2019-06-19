<?php

namespace App\Bot\Interpreters;

use OpenDialogAi\ContextEngine\Facades\AttributeResolver;
use OpenDialogAi\Core\Conversation\Intent;
use OpenDialogAi\Core\Utterances\TextUtterance;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\InterpreterEngine\BaseInterpreter;
use OpenDialogAi\InterpreterEngine\Interpreters\NoMatchIntent;

class DefaultInputInterpreter extends BaseInterpreter
{
    protected static $name = 'interpreter.shoebot.input';

    const MATCH = 'intent.core.match';

    /**
     * Interprets an utterance and returns all matching intents in an array
     *
     * @param UtteranceInterface $utterance
     * @return Intent[]
     * @throws \OpenDialogAi\Core\Utterances\Exceptions\FieldNotSupported
     */
    public function interpret(UtteranceInterface $utterance): array
    {
        $intent = new NoMatchIntent();
        if ($utterance->getType() === TextUtterance::TYPE) {
            $intent->setId(self::MATCH);

            $inputAttribute = AttributeResolver::getAttributeFor('input', $utterance->getText());
            $intent->addAttribute($inputAttribute);

        }

        return [$intent];
    }
}