<?php

namespace App\Bot\Interpreters;

use Google\Cloud\Dialogflow\V2\Intent\Message;
use Google\Protobuf\Internal\RepeatedField;
use OpenDialogAi\MessageBuilder\MessageMarkUpGenerator;

class DialogflowMessageTransformer
{
    /**
     * @param Message $responseMessage
     * @param Message|null $previousResponseMessage
     * @param string $defaultResponse
     * @param array $listOfMessages
     * @return string
     */
    public static function interpretMessages(
        Message $responseMessage,
        ?Message $previousResponseMessage,
        string $defaultResponse,
        array &$listOfMessages
    ) {
        $messageGenerator = new MessageMarkUpGenerator();

        if ($simpleResponses = $responseMessage->getSimpleResponses()) {
            foreach ($simpleResponses->getSimpleResponses() as $simpleResponse) {
                $messageText = $simpleResponse->getTextToSpeech();
                $messageText = self::formatLinks($messageText);
                $messageGenerator->addTextMessage($messageText);
            }
        }

        if ($basicCard = $responseMessage->getBasicCard()) {
            $title = $basicCard->getTitle();
            $subtitle = $basicCard->getSubtitle();
            $text = $basicCard->getFormattedText();

            $image = [];
            if ($cardImage = $basicCard->getImage()) {
                $imageSrc = $cardImage->getImageUri();

                $image = [
                    'src' => $imageSrc,
                    'url' => '',
                    'new_tab' => true,
                ];
            }

            $buttons = [];
            $buttonLink = '';
            foreach ($basicCard->getButtons() as $button) {
                $buttonText = $button->getTitle();
                $buttonLink = $button->getOpenUriAction()->getUri();

                $buttons[] = [
                    'text' => $buttonText,
                    'link' => $buttonLink,
                    'link_new_tab' => true,
                ];
            }

            $link = count($buttons) == 1 ? $buttonLink : '';
            $messageGenerator->addRichMessage($title, $subtitle, $text, '', '', $link, $buttons, $image);
        }

        if ($browseCarouselCard = $responseMessage->getBrowseCarouselCard()) {
            $messages = [];

            foreach ($browseCarouselCard->getItems() as $browseCarouselCardItem) {
                $message = [
                    'rich' => [
                        'title' => $browseCarouselCardItem->getTitle(),
                        'subtitle' => '',
                        'text' => $browseCarouselCardItem->getDescription(),
                        'callback' => '',
                        'callback_value' => '',
                        'link' => $browseCarouselCardItem->getOpenUriAction()->getUrl(),
                    ],
                ];

                if ($image = $browseCarouselCardItem->getImage()) {
                    $message['rich']['image'] = [
                        'src' => $image->getImageUri(),
                        'url' => '',
                        'new_tab' => false,
                    ];
                }

                $messages[] = $message;
            }

            $messageGenerator->addListMessage('vertical', '', $messages);
        }

        if ($suggestions = $responseMessage->getSuggestions()) {
            $repeatedField = $suggestions->getSuggestions();
            self::addButtonMessage($messageGenerator, $repeatedField, $defaultResponse, $previousResponseMessage);

            // Remove the previously added text message as we've just used its text for the button message
            if (!is_null($previousResponseMessage)) {
                array_pop($listOfMessages);
            }
        }

        if ($quickReplies = $responseMessage->getQuickReplies()) {
            $repeatedField = $quickReplies->getQuickReplies();
            self::addButtonMessage(
                $messageGenerator,
                $repeatedField,
                $defaultResponse,
                $previousResponseMessage,
                false,
                $quickReplies->getTitle() == "" ? null : $quickReplies->getTitle()
            );
        }

        if ($linkOutSuggestion = $responseMessage->getLinkOutSuggestion()) {
            $buttons = [
                [
                    'text' => $linkOutSuggestion->getDestinationName(),
                    'link' => $linkOutSuggestion->getUri(),
                    'link_new_tab' => true,
                ],
            ];

            $messageText = $defaultResponse;
            if ($previousResponseMessage) {
                if ($simpleResponses = $previousResponseMessage->getSimpleResponses()) {
                    foreach ($simpleResponses->getSimpleResponses() as $simpleResponse) {
                        $messageText = $simpleResponse->getTextToSpeech();
                    }
                }
            }

            $messageGenerator->addButtonMessage($messageText, $buttons);
        }

        if ($listSelect = $responseMessage->getListSelect()) {
            $title = $listSelect->getTitle();
            $messages = [];

            foreach ($listSelect->getItems() as $listSelectItem) {
                $message = [
                    'rich' => [
                        'title' => $listSelectItem->getTitle(),
                        'subtitle' => '',
                        'text' => $listSelectItem->getDescription(),
                        'callback' => AbstractDialogflowInterpreter::DIALOG_FLOW_INTENT,
                        'callback_value' => $listSelectItem->getTitle(),
                        'link' => '',
                    ],
                ];

                if ($image = $listSelectItem->getImage()) {
                    $message['rich']['image'] = [
                        'src' => $image->getImageUri(),
                        'url' => '',
                        'new_tab' => false,
                    ];
                }

                $messages[] = $message;
            }

            $messageGenerator->addListMessage('list', $title, $messages);
        }

        if ($carouselSelect = $responseMessage->getCarouselSelect()) {
            $messages = [];

            foreach ($carouselSelect->getItems() as $carouselSelectItem) {
                $message = [
                    'rich' => [
                        'title' => $carouselSelectItem->getTitle(),
                        'subtitle' => '',
                        'text' => $carouselSelectItem->getDescription(),
                        'callback' => AbstractDialogflowInterpreter::DIALOG_FLOW_INTENT,
                        'callback_value' => $carouselSelectItem->getInfo()->getKey(),
                        'link' => '',
                    ],
                ];

                if ($image = $carouselSelectItem->getImage()) {
                    $message['rich']['image'] = [
                        'src' => $image->getImageUri(),
                        'url' => '',
                        'new_tab' => false,
                    ];
                }

                $messages[] = $message;
            }

            $messageGenerator->addListMessage('horizontal', '', $messages);
        }

        return $messageGenerator->getMessagesMarkUp();
    }

    /**
     * @param MessageMarkUpGenerator $messageGenerator
     * @param RepeatedField $repeatedField
     * @param string $defaultResponse
     * @param Message|null $previousResponseMessage
     * @param bool $external
     * @param string|null $messageText
     * @return array
     */
    private static function addButtonMessage(
        MessageMarkUpGenerator $messageGenerator,
        RepeatedField $repeatedField,
        string $defaultResponse,
        ?Message $previousResponseMessage,
        bool $external = true,
        ?string $messageText = null
    ): void {
        $buttons = [];

        foreach ($repeatedField as $field) {
            $value = is_string($field) ? $field : $field->getTitle();

            $buttons[] = [
                'text' => $value,
                'value' => $value,
                'callback' => AbstractDialogflowInterpreter::DIALOG_FLOW_INTENT,
            ];
        }

        if (is_null($messageText)) {
            $messageText = $defaultResponse;
            if ($previousResponseMessage) {
                if ($simpleResponses = $previousResponseMessage->getSimpleResponses()) {
                    foreach ($simpleResponses->getSimpleResponses() as $simpleResponse) {
                        $messageText = $simpleResponse->getTextToSpeech();
                    }
                }
            }
        }

        $messageGenerator->addButtonMessage($messageText, $buttons, $external);
    }

    /**
     * @param $text
     * @return string
     */
    public static function formatLinks($text)
    {
        $regex = '/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU';
        preg_match_all($regex, $text, $links, PREG_SET_ORDER);

        $responseText = $text;

        foreach ($links as $link) {
            $linkTag = $link[0];
            $linkUrl = $link[2];
            $linkText = $link[3];

            $linkMarkup = sprintf(
                '<link new_tab="true"><url>%s</url><text>%s</text></link>',
                $linkUrl,
                $linkText
            );

            $responseText = str_replace($linkTag, $linkMarkup, $responseText);
        }

        return $responseText;
    }
}
