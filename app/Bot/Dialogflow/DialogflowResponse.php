<?php


namespace App\Bot\Dialogflow;

use Google\Cloud\Dialogflow\V2\Intent\Message;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUEntity;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUResponse;

class DialogflowResponse extends AbstractNLUResponse
{
    /**
     * @var array
     */
    private $responseMessages;

    /**
     * @var bool
     */
    private $completing;

    private $response;

    /**
     * DialogflowResponse constructor.
     * @param array $requestContents
     */
    public function __construct(array $requestContents)
    {
        $this->query = $requestContents['queryText'];
        $this->topScoringIntent = new DialogflowIntent(
            $requestContents['intent']['displayName'],
            $requestContents['intentDetectionConfidence']
        );

        $this->response = $requestContents['fulfillmentText'];

        $this->responseMessages = array_map(function ($messageData) {
            $message = new Message();
            $message->setText(new Message\Text([
                'text' => $messageData['text']['text']
            ]));
            return $message;
        }, $requestContents['fulfillmentMessages']);

        if (!empty($requestContents['parameters']['fields'])) {
            $entities = [];
            foreach ($requestContents['parameters']['fields'] as $key => $value) {
                $entities[$key] = $value;
            }
            $this->createEntities($entities);
        }
    }

    /**
     * @param array $entities
     */
    public function createEntities(array $entities): void
    {
        foreach ($entities as $parameterName => $parameterValue) {
            $this->entities[] = $this->createEntity([
                'name' => $parameterName,
                'value' => $parameterValue['stringValue']
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function createEntity($entity): AbstractNLUEntity
    {
        return new DialogflowEntity($entity);
    }

    /**
     * @return array
     */
    public function getResponseMessages(): array
    {
        return $this->responseMessages;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isCompleting(): bool
    {
        return $this->completing;
    }

    /**
     * @param bool $completing
     */
    public function setCompleting(bool $completing): void
    {
        $this->completing = $completing;
    }
}
