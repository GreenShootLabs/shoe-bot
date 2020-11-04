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
        $currentConversation = $actionInput->getAttributeBag()->getAttributeValue('current_conversation');

        Log::debug(sprintf('%s detecting %s as current conversation.', self::$name, $currentConversation));

        return ActionResult::createSuccessfulActionResultWithAttributes([
            AttributeResolver::getAttributeFor(
                'last_conversation',
                $currentConversation
            )
        ]);
    }
}
