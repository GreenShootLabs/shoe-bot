<?php

return [

    /**
     * Register your application specific attributes here. They should be registered with:
     * {attribute_name} => Fully/Qualified/ClassName
     *
     * Where ClassName is an implementation of @see \OpenDialogAi\Core\Attribute\AttributeInterface
     */
    'custom_attributes' => [
        'last_conversation' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'is_resuming' => \OpenDialogAi\Core\Attribute\BooleanAttribute::class,
        'is_free_pronation_request' => \OpenDialogAi\Core\Attribute\BooleanAttribute::class,

        'dialogflow_message' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'experience' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'surface' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'sturdiness' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'gender' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'size' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'distance' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'frequency' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'pronation' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'shoe_1_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'shoe_1_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'shoe_2_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'shoe_2_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'shoe_3_img' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'shoe_3_desc' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'selection' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'selection_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'title' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'first_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'last_name' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'street' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'city' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
        'post_code' => \OpenDialogAi\Core\Attribute\StringAttribute::class,

        'agreement' => \OpenDialogAi\Core\Attribute\BooleanAttribute::class,
        'survey_answer' => \OpenDialogAi\Core\Attribute\StringAttribute::class,
    ],

    /**
     * Register your custom contexts here. Custom contexts must extend
     * @see \OpenDialogAi\ContextEngine\Contexts\Custom\AbstractCustomContext
     *
     * Custom contexts are used to make available application specific attributes that are externally managed
     */
    'custom_contexts' => [
    ]
];
