<?php

namespace App\Domain\Reviews;

class Review
{
    /**
     * @var string
     */
    private string $author;

    /**
     * @var string
     */
    private string $text;

    /**
     * @var string|null
     */
    private ?string $ocUser;

    /**
     * @var Stars
     */
    private Stars $stars;

    /**
     * @var string[]
     */
    private array $possibleUsers;

    /**
     * @param string $author
     * @param string $text
     * @param string|null $ocUser
     */
    public function __construct(string $author, string $text, string $ocUser = null)
    {
        $this->author = $author;
        $this->text = $text;
        $this->ocUser = strtolower($ocUser);
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setStars(Stars $stars): Review
    {
        $this->stars = $stars;
        return $this;
    }

    public function addPossibleUser(string $user): Review
    {
        $this->possibleUsers[] = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getOcUser(): ?string
    {
        return $this->ocUser;
    }

    /**
     * @return Stars
     */
    public function getStars(): Stars
    {
        return $this->stars;
    }

    /**
     * @return string[]
     */
    public function getPossibleUsers(): array
    {
        return $this->possibleUsers;
    }
}
