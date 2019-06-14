<?php

namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\ContextManager\ContextService;

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
        /** @var ContextService $contextService */
        $contextService = app()->make(ContextService::class);

        $userContext = $contextService->getUserContext();

        collect(self::$userAttributes)->each(function (string $attributeName) use ($userContext) {
            $userContext->removeAttribute($attributeName);
        });

        $userContext->updateUser();

        return new ActionResult(true);
    }
}
