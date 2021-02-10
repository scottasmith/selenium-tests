<?php

namespace App\Domain\Sml\Requests;

use Carbon\Carbon;

class CreateNoLicenceRequest implements RequestInterface
{
    public const GENDER_M = 'M';
    public const GENDER_F = 'F';

    private string $niNumber;

    private string $forename;

    private string $surname;

    private string $postcode;

    private string $gender;

    private Carbon $dob;

    /**
     * @param string $niNumber
     * @param string $forename
     * @param string $surname
     * @param string $postcode
     * @param string $gender
     * @param Carbon $dob
     * @throws \Exception
     */
    public function __construct(
        string $niNumber,
        string $forename,
        string $surname,
        string $postcode,
        string $gender,
        Carbon $dob
    ) {
        if (!in_array($gender, [self::GENDER_F, self::GENDER_M])) {
            throw new \Exception('Invalid gender: ' . $gender);
        }

        $this->niNumber = $niNumber;
        $this->forename = $forename;
        $this->surname = $surname;
        $this->postcode = $postcode;
        $this->gender = $gender;
        $this->dob = $dob;
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
    public function getForename(): string
    {
        return $this->forename;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return Carbon
     */
    public function getDob(): Carbon
    {
        return $this->dob;
    }
}
