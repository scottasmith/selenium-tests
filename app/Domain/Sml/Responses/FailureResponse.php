<?php

namespace App\Domain\Sml\Responses;

class FailureResponse implements ResponseInterface
{
    protected string $errorMessage;

    public function __construct(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getMessage(): string
    {
        return $this->errorMessage;
    }
}
