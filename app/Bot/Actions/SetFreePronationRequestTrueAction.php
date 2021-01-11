<?php


namespace App\Bot\Actions;

use Illuminate\Support\Facades\Log;
use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;

class SetFreePronationRequestTrueAction extends BaseAction
{
    protected static $name = 'action.shoebot.setFreePronationRequestTrue';

    protected $outputAttributes = ['is_free_pronation_request'];

    public function perform(ActionInput $actionInput): ActionResult
    {
        Log::debug(sprintf('%s setting is_free_pronation_request to true.', self::$name));

        return ActionResult::createSuccessfulActionResultWithAttributes([
            AttributeResolver::getAttributeFor(
                'is_free_pronation_request',
                true
            )
        ]);
    }
}
