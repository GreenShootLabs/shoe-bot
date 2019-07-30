<?php

namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\ContextService;

class RefreshUserAction extends BaseAction
{
    protected $performs = 'action.shoebot.user_refresh';

    private static $userAttributes = [
        'experience',
        'distance',
        'surface',
        'frequency'
    ];

    public function perform(ActionInput $actionInput): ActionResult
    {
        $userContext = ContextService::getUserContext();

        collect(self::$userAttributes)->each(function (string $attributeName) use ($userContext) {
            $userContext->removeAttribute($attributeName);
        });

        $userContext->updateUser();

        return new ActionResult(true);
    }
}
