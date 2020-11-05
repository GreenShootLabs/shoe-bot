<?php


namespace App\Bot\Interpreters\MainInterpreters;

use App\Bot\Dialogflow\DialogflowClient;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\Core\Conversation\Intent;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUEntity;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLURequestFailedException;
use OpenDialogAi\InterpreterEngine\Interpreters\CallbackInterpreter;
use OpenDialogAi\InterpreterEngine\Interpreters\NoMatchIntent;

abstract class AbstractMainInterpreter extends CallbackInterpreter
{
    protected static int $interpreterNumber = -1;

    private array $map = [
        0 => 'welcome_conversation',
        1 => 'get_runner_experience_conversation',
        2 => 'provide_runner_experience_conversation',
        3 => 'work_out_pronation_conversation',
        4 => 'recommendation_conversation',
        5 => 'purchase_conversation',
        6 => 'personal_information_conversation',
        7 => 'payment_request_conversation',
        8 => 'feedback_conversation',
    ];

    private $intentsToIgnore = [
         NoMatchIntent::NO_MATCH,
        'intent.shoebot.promptQuestion',
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
                && !in_array($utterance->getCallbackId(), $this->intentsToIgnore)) {
                if ($utterance->getCallbackId() != '' && $utterance->getCallbackId() != 'WELCOME') {
                    $intent = Intent::createIntentWithConfidence('intent.shoebot.resume', 1);
                    $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', false));

                    $this->setValue($utterance, $intent);
                    $this->setFormValues($utterance, $intent);

                    return [$intent];
                } elseif ($utterance->getText()) {
                    $client = resolve(DialogflowClient::class);
                    $client->setDefaultProjectId(config('opendialog.interpreter_engine.dialogflow_config.main.project_id'));
                    try {
                        $response = $client->query($utterance->getText());
                    } catch (AbstractNLURequestFailedException $e) {
                        Log::warning("Client call failed with a non 200 response, please check the logs");
                        return [];
                    }

                    Log::debug(sprintf(
                        '%s [continuous] (Dialogflow) expected %s and got %s.',
                        static::$name,
                        $relevantConversationContinuous,
                        $response->getTopScoringIntent()->getLabel()
                    ));

                    if ($relevantConversationContinuous == $response->getTopScoringIntent()->getLabel()) {
                        $intent = Intent::createIntentWithConfidence('intent.shoebot.resume', 1);
                        $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', false));

                        /** @var AbstractNLUEntity $entity */
                        foreach ($response->getEntities() as $entity) {
                            $value = $entity->getResolutionValues()[0];

                            if ($value != "") {
                                $intent->addAttribute(
                                    AttributeResolver::getAttributeFor($entity->getType(), $value)
                                );
                            }
                        }

                        return [$intent];
                    } else {
                        return [];
                    }
                } else {
                    return [];
                }
            } else {
                return [];
            }
        }
    }
}
