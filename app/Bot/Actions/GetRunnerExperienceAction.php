<?php


namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;

class GetRunnerExperienceAction extends BaseAction
{
    protected static $name = 'action.shoebot.get_runner_experience';

    public function perform(ActionInput $actionInput): ActionResult
    {
        $startedDiscovery = AttributeResolver::getAttributeFor("started_discovery", "true");

        $result = new ActionResult(true);
        $result->addAttribute($startedDiscovery);
        return $result;
    }
}
