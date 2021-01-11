<?php


namespace App\Bot\Actions;

use Illuminate\Support\Facades\Log;
use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;

class TrackProgressAction extends BaseAction
{
    protected static $name = 'action.shoebot.trackProgress';

    protected $requiredAttributes = ['current_conversation'];
    protected $outputAttributes = ['last_conversation'];

    public function perform(ActionInput $actionInput): ActionResult
    {
        $attributeBag = $actionInput->getAttributeBag();

        $isFreePronationRequest = false;
        $lastConversation = null;
        $currentConversation = $attributeBag->getAttributeValue('current_conversation');

        if ($attributeBag->hasAttribute('is_free_pronation_request')) {
            $isFreePronationRequest = $attributeBag->getAttributeValue('is_free_pronation_request');
        }

        if ($attributeBag->hasAttribute('last_conversation')) {
            $lastConversation = $attributeBag->getAttributeValue('last_conversation');
        }

        if ($isFreePronationRequest && !is_null($lastConversation)
            && $currentConversation === 'work_out_pronation_conversation') {
            // If the user came to the work out pronation conversation by a free request (eg. they typed "what is pronation")
            // then do not progress them, but send them back to there previous conversation
            $currentConversation = $lastConversation;
        }

        Log::debug(sprintf('%s detecting %s as current conversation.', self::$name, $currentConversation));

        return ActionResult::createSuccessfulActionResultWithAttributes([
            AttributeResolver::getAttributeFor(
                'last_conversation',
                $currentConversation
            )
        ]);
    }
}
