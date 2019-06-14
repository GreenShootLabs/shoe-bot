<?php

return [

    /**
     * Register your application specific attributes here. They should be registered with:
     * {attribute_name} => Fully/Qualified/ClassName
     *
     * Where ClassName is an implementation of @see \OpenDialogAi\Core\Attribute\AttributeInterface
     */
    'custom_attributes' => [
         'experience' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'distance' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'frequency' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'surface' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'sturdiness' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'footType' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'gender' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_1_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_1_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_2_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_2_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_3_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
         'shoe_3_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
    ],

    /**
     * Register your custom contexts here. Custom contexts must extend
     * @see \OpenDialogAi\ContextEngine\Contexts\Custom\AbstractCustomContext
     *
     * Custom contexts are used to make available application specific attributes that are externally managed
     */
    'custom_contexts' => [
//        \OpenDialogAi\ContextEngine\tests\contexts\DummyCustomContext::class
    ]
];
