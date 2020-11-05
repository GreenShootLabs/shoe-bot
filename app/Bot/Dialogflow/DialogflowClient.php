<?php

namespace App\Bot\Dialogflow;

use Ds\Map;
use Google\ApiCore\ValidationException;
use OpenDialogAi\ContextEngine\Facades\ContextService;
use OpenDialogAi\Core\Conversation\UserAttribute;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUCustomClient;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUCustomRequest;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLURequestFailedException;
use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUResponse;

class DialogflowClient extends AbstractNLUCustomClient
{
    /**
     * @var string
     */
    private $languageCode;

    /**
     * @var string
     */
    private $defaultProjectId;

    /**
     * @var string
     */
    private $defaultEnvironment;

    /** @var array */
    private $allowedAttributes = [
        'authenticated',
        'language',
        'country',
        'userid',
        'first_name',
        'last_name',
        'email_address',
        'business_phone',
        'community',
        'company_name',
        'companyid',
        'product',
    ];

    public const WELCOME_EVENT = 'Welcome';

    /**
     * @inheritDoc
     */
    public function __construct($config)
    {
        $this->languageCode = $config['language_code'] ?? 'en-GB';
        $this->defaultEnvironment = $config['environment'] ?? 'draft';
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode(string $languageCode): void
    {
        $this->languageCode = $languageCode;
    }

    /**
     * @return string
     */
    public function getDefaultProjectId(): string
    {
        return $this->defaultProjectId;
    }

    /**
     * @param string $defaultProjectId
     */
    public function setDefaultProjectId(string $defaultProjectId): void
    {
        $this->defaultProjectId = $defaultProjectId;
    }

    /**
     * @return string
     */
    public function getDefaultEnvironment(): string
    {
        return $this->defaultEnvironment;
    }

    /**
     * @param string $environment
     * @return void
     */
    public function setDefaultEnvironment(string $environment): void
    {
        $this->defaultEnvironment = $environment;
    }

    /**
     * @inheritDoc
     * @throws AbstractNLURequestFailedException
     * @throws ValidationException
     */
    public function sendRequest($message, $projectId = null, $environment = null): AbstractNLUCustomRequest
    {
        $projectId = $projectId ?? $this->getDefaultProjectId();
        $sessionId = ContextService::getUserContext()->getUserId() ?: uniqid();

        $client = new \GuzzleHttp\Client();
        $response = $client->post($projectId, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'text' => $message,
                'session' => $sessionId,
            ]
        ]);
        $body = $response->getBody();
        $data = json_decode($body, true);
        $queryResultData = $data['queryResult'];

        return new DialogflowRequest($queryResultData);
    }

    /**
     * @inheritDoc
     */
    public function createResponse($response): AbstractNLUResponse
    {
        return new DialogflowResponse($response);
    }

    /**
     * @param Map $attributes
     * @return Map
     */
    private function filterAttributes(Map $attributes): Map
    {
        return $attributes->filter(function ($key, UserAttribute $attribute) {
            return in_array($attribute->getId(), $this->allowedAttributes);
        });
    }
}
