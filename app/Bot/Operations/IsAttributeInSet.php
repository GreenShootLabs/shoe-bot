<?php


namespace App\Bot\Operations;

use OpenDialogAi\OperationEngine\BaseOperation;

class IsAttributeInSet extends BaseOperation
{
    public static $name = 'is_attribute_in_set';

    public function performOperation(): bool
    {
        $firstAvailableAttribute = null;

        foreach ($this->attributes as $attribute) {
            if (is_null($attribute)) {
                continue;
            } else {
                $firstAvailableAttribute = $attribute;
                break;
            }
        }

        $value = str_getcsv($this->parameters['value'], '.');
        return in_array($firstAvailableAttribute->getValue(), $value);
    }

    public static function getAllowedParameters(): array
    {
        return [
            'required' => [
                'value',
            ],
        ];
    }
}
