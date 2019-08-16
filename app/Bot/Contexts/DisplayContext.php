<?php

namespace App\Bot\Contexts;

use Ds\Map;
use OpenDialogAi\ContextEngine\Contexts\Custom\AbstractCustomContext;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\Core\Attribute\AttributeInterface;
use OpenDialogAi\Core\Attribute\StringAttribute;

/**
 * Generic context
 *
 */
class DisplayContext extends AbstractCustomContext
{
    public static $name = 'display';

    private $displayMap;

    public function __construct()
    {
        parent::__construct();

        $this->displayMap = new Map([
            "once" => "once or twice",
            "three" => "three or more times",
        ]);
    }

    public function loadAttributes(): void
    {
        // N/A
    }

    /**
     * Gets the value from the user context and attempts to map it to a display value. If no value exists in the map,
     * it defaults to the original value.
     * @param string $attributeName
     * @return AttributeInterface
     */
    public function getAttribute(string $attributeName): AttributeInterface
    {
        $userContext = ContextService::getUserContext();
        $userValue = $userContext->getAttributeValue($attributeName);
        $displayValue = $this->displayMap->get($userValue, $userValue);

        return new StringAttribute($attributeName, $displayValue);
    }
}
