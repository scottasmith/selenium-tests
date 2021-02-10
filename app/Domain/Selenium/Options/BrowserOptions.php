<?php

namespace App\Domain\Selenium\Options;

class BrowserOptions
{
    /**
     * @var string
     */
    private string $host;

    /**
     * @var string|null
     */
    private ?string $proxy = null;

    /**
     * @var Rect
     */
    private ?Rect $windowRect = null;

    /**
     * BrowserOptions constructor.
     * @param string $host
     * @param Rect $windowRect|null
     */
    public function __construct(string $host, ?Rect $windowRect = null)
    {
        $this->host = $host;
        $this->windowRect = $windowRect;
    }

    /**
     * @param string|null $proxy
     * @return BrowserOptions
     */
    public function setProxy(?string $proxy): BrowserOptions
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getProxy(): ?string
    {
        return $this->proxy;
    }

    /**
     * @return Rect|null
     */
    public function getWindowRect(): ?Rect
    {
        return $this->windowRect;
    }
}
