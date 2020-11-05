<?php

namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;

class GetSelectionNameAction extends BaseAction
{
    protected static $name = 'action.shoebot.getSelectioName';

    protected $requiredAttributes = [
        'current_conversation',
        'gender',
        'selection',
    ];

    protected $outputAttributes = [
        'last_conversation',
        'selection_name',
    ];

    private $numberToIntMap = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
    ];

    public function perform(ActionInput $actionInput): ActionResult
    {
        $trackingResult = (new TrackProgressAction())->perform(
            (new ActionInput())->addAttribute(
                $actionInput->getAttributeBag()->getAttribute('current_conversation')
            )
        );

        $gender = $actionInput->getAttributeBag()->getAttributeValue('gender');
        $selection = $this->numberToIntMap[$actionInput->getAttributeBag()->getAttributeValue('selection')];

        $attributes = array_values($trackingResult->getResultAttributes()->getAttributes()->toArray());

        $attributes[] = AttributeResolver::getAttributeFor('selection_name', GetShoesAction::$shoes[$gender][$selection][1]);

        return ActionResult::createSuccessfulActionResultWithAttributes($attributes);
    }
}
