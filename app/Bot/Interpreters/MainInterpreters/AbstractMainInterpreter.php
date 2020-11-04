<?php


namespace App\Bot\Interpreters\MainInterpreters;

use Illuminate\Support\Facades\Log;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\Core\Conversation\Intent;
use OpenDialogAi\Core\Utterances\TextUtterance;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\InterpreterEngine\BaseInterpreter;
use OpenDialogAi\InterpreterEngine\Interpreters\NoMatchIntent;

abstract class AbstractMainInterpreter extends BaseInterpreter
{
    protected static int $interpreterNumber = -1;

    private array $map = [
        0 => 'welcome_conversation',
        1 => 'question_one_conversation',
        2 => 'question_two_conversation',
        3 => 'question_three_conversation',
    ];

    /**
     * @return string
     * @throws MainInterpreterNumberNotSetException
     */
    public static function getInterpreterNumber(): int
    {
        if (static::$interpreterNumber === self::$interpreterNumber) {
            throw new MainInterpreterNumberNotSetException(sprintf("Interpreter %s has not defined a number", __CLASS__));
        }
        return static::$interpreterNumber;
    }

    public function interpret(UtteranceInterface $utterance): array
    {
        $lastConversation = ContextService::getUserContext()->getAttributeValue('last_conversation');
        $relevantConversationAfterFaq = $this->map[self::getInterpreterNumber()];

        if (self::getInterpreterNumber()-1 > -1) {
            $relevantConversationContinuous = $this->map[self::getInterpreterNumber()-1];
        } else {
            $relevantConversationContinuous = null;
        }

        if ($utterance->getCallbackId() == 'intent.shoebot.resume') {
            Log::debug(sprintf(
                '%s [resuming] expected %s and got %s.',
                static::$name,
                $relevantConversationAfterFaq,
                $lastConversation
            ));

            if ($relevantConversationAfterFaq == $lastConversation) {
                $intent = Intent::createIntentWithConfidence('intent.shoebot.resume', 1);
                $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', true));
                return [$intent];
            } else {
                return [];
            }
        } else {
            Log::debug(sprintf(
                '%s [continuous] expected %s and got %s.',
                static::$name,
                $relevantConversationContinuous,
                $lastConversation
            ));

            if ($relevantConversationContinuous == $lastConversation
             && (($utterance->getCallbackId() != '' && $utterance->getCallbackId() != 'WELCOME')
                    || in_array(trim(strtolower($utterance->getText())), ['y', 'n']))) {
                $intent = Intent::createIntentWithConfidence('intent.shoebot.resume', 1);
                $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', false));
                return [$intent];
            } else {
                return [];
            }
        }
    }
}
