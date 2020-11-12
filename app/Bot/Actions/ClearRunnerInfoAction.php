<?php

namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;
use OpenDialogAi\ContextEngine\Facades\ContextService;

class ClearRunnerInfoAction extends BaseAction
{
    protected static $name = 'action.shoebot.clearRunnerInfo';

    public function perform(ActionInput $actionInput): ActionResult
    {
        $attributesToRemove = [
            'gender',
            'frequency',
            'pronation',
            'last_conversation'
        ];

        foreach ($attributesToRemove as $attributeName) {
            ContextService::getUserContext()->addAttribute(AttributeResolver::getAttributeFor($attributeName, null));
        }

        ContextService::getUserContext()->persist();

        return ActionResult::createSuccessfulActionResultWithAttributes([]);
    }
}
