<?php

namespace App\Domain\Sml\Responses;

class CreateSuccessResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private string $dln;

    /**
     * @var string
     */
    private string $checkCode;

    /**
     * SuccessResponse constructor.
     * @param string $dln
     * @param string $checkCode
     */
    public function __construct(string $dln, string $checkCode)
    {
        $this->dln = $dln;
        $this->checkCode = $checkCode;
    }

    /**
     * @return string
     */
    public function getDln(): string
    {
        return $this->dln;
    }

    /**
     * @return string
     */
    public function getCheckCode(): string
    {
        return $this->checkCode;
    }
}
