<?php


namespace App\Bot\Dialogflow;

use OpenDialogAi\InterpreterEngine\Interpreters\AbstractNLUInterpreter\AbstractNLUCustomRequest;

class DialogflowRequest extends AbstractNLUCustomRequest
{
    /**
     * @var array
     */
    private $contents;

    /**
     * @var bool
     */
    private $successful;

    /**
     * DialogflowRequest constructor.
     * @param array $queryResult
     */
    public function __construct(array $queryResult)
    {
        $this->contents = $queryResult;
        $this->successful = !is_null($queryResult);
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }
}
