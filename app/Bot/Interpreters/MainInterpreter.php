<?php


namespace App\Bot\Interpreters;

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

class MainInterpreter extends CallbackInterpreter
{
    protected static $name = 'interpreter.shoebot.main';

    private $intentsToIgnore = [
         NoMatchIntent::NO_MATCH,
        'intent.shoebot.promptQuestion',
        'intent.app.end_chat'
    ];

    public function interpret(UtteranceInterface $utterance): array
    {
        $currentConversation = ContextService::getConversationContext()->getAttributeValue('current_conversation');

        if ($utterance->getCallbackId() == 'intent.shoebot.resume') {
            $intent = Intent::createIntentWithConfidence('intent.shoebot.resume', 1);

            if ($currentConversation == 'no_match_conversation') {
                $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', true));
            } else {
                $intent->addAttribute(AttributeResolver::getAttributeFor('is_resuming', false));
            }

            return [$intent];
        } else {
            if (!in_array($utterance->getCallbackId(), $this->intentsToIgnore)) {
                if ($utterance->getCallbackId() != '') {
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

                    $intent = Intent::createIntentWithConfidence(
                        $response->getTopScoringIntent()->getLabel(),
                        $response->getTopScoringIntent()->getConfidence()
                    );

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
        }
    }
}
