<?php

namespace App\Domain\Sml\Requests;

class CreateWithLicenceRequest implements RequestInterface
{
    /**
     * @var string
     */
    private string $dln;

    /**
     * @var string
     */
    private string $niNumber;

    /**
     * @var string
     */
    private string $postcode;

    /**
     * @param string $dln
     * @param string $niNumber
     * @param string $postcode
     */
    public function __construct(string $dln, string $niNumber, string $postcode)
    {
        $this->dln = $dln;
        $this->niNumber = $niNumber;
        $this->postcode = $postcode;
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
    public function getNiNumber(): string
    {
        return $this->niNumber;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }
}
