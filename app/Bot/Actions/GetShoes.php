<?php

namespace App\Bot\Actions;

use OpenDialogAi\ActionEngine\Actions\ActionInput;
use OpenDialogAi\ActionEngine\Actions\ActionResult;
use OpenDialogAi\ActionEngine\Actions\BaseAction;
use OpenDialogAi\ContextEngine\ContextManager\ContextService;
use OpenDialogAi\ContextEngine\Facades\AttributeResolver;

class GetShoes extends BaseAction
{
    protected $performs = 'action.shoebot.get_shoes';

    private static $shoes = [
        'male' => [
            1 => [
                '/bot/images/mens_1.jpg',
                'KALENJI MEN\'S TRAIL RUNNING SHOES TR - BLACK AND BRONZE'
            ],
            2 => [
                '/bot/images/mens_2.jpg',
                'SALOMON SALOMON SPEEDCROSS 4 MEN\'S TRAIL RUNNING SHOES - BLACK'
            ],
            3 => [
                '/bot/images/mens_3.jpg',
                'ASICS ASICS GEL FUJI TRABUCO 7 MEN\'S TRAIL RUNNING SHOES GREY/BLUE'
            ],
        ],
        'female' => [
            1 => [
                '/bot/images/womens_trail_1.jpg',
                'KALENJI KIPRUN TRAIL TR WOMEN\'S TRAIL RUNNING SHOES - PINK/MAROON'
            ],
            2 => [
                '/bot/images/womens_trail_2.jpg',
                'KALENJI KIPRUN RACE 4 WOMEN\'S TRAIL RUNNING SHOES - GREY/YELLOW'
            ],
            3 => [
                '/bot/images/womens_trail_3.jpg',
                'KALENJI XT7 WOMEN\'S TRAIL RUNNING SHOES BLACK AND BRONZE'
            ],
        ],

    ];

    public function perform(ActionInput $actionInput): ActionResult
    {
        /** @var ContextService $contextService */
        $contextService = app()->make(ContextService::class);

        $userContext = $contextService->getUserContext();

        $gender = $userContext->getAttribute('gender')->getValue();

        $attributes = [];

        for ($i = 1; $i < 4; $i++) {
            $attributes[] = AttributeResolver::getAttributeFor("shoe_{$i}_img", self::$shoes[$gender][$i][0]);
            $attributes[] = AttributeResolver::getAttributeFor("shoe_{$i}_desc", self::$shoes[$gender][$i][1]);
        }

        return ActionResult::createSuccessfulActionResultWithAttributes($attributes);
    }
}
