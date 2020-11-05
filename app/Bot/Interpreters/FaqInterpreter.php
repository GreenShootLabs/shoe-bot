<?php


namespace App\Bot\Interpreters;

use App\Bot\Dialogflow\DialogflowClient;
use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\Utterances\UtteranceInterface;
use OpenDialogAi\InterpreterEngine\Interpreters\NoMatchIntent;

class FaqInterpreter extends AbstractDialogflowInterpreter
{
    protected static $name = 'interpreter.shoebot.faq';

    public function interpret(UtteranceInterface $utterance): array
    {
        $text = $utterance->getText();

        if ($utterance->getCallbackId() == NoMatchIntent::NO_MATCH) {
            if (!is_null($text) && $text != '') {
                Log::debug(sprintf('%s: received text and is querying Dialogflow', self::getName()));
                $client = resolve(DialogflowClient::class);
                $client->setDefaultProjectId(config('opendialog.interpreter_engine.dialogflow_config.faq.project_id'));
                return self::interpretWithClient($utterance, $client);
            } else {
                return [new NoMatchIntent()];
            }
        } else {
            return [];
        }
    }
}
