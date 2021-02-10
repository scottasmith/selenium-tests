<?php

namespace App\Domain\Selenium;

use App\Domain\Selenium\Traits\ElementActions;
use App\Domain\Selenium\Traits\ElementDriver;
use App\Domain\Selenium\Traits\ElementSelectors;
use Closure;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;

class Element implements ElementInterface
{
    use ElementSelectors;
    use ElementActions;
    use ElementDriver;

    private Browser $browser;

    private string $selector;

    private string $type;

    /**
     * @var Browser|Element|Elements|null
     */
    private $rootElement;

    private ?RemoteWebElement $remoteElement;

    /**
     * Element constructor.
     * @param Browser $browser
     * @param string $selector
     * @param string|null $type
     * @param Browser|Element|Elements|null $rootElement
     * @param RemoteWebElement|null $remoteElement
     * @throws Exception
     */
    public function __construct(
        Browser $browser,
        string $selector,
        string $type = null,
        $rootElement = null,
        RemoteWebElement $remoteElement = null
    ) {
        $this->browser = $browser;
        $this->selector = $selector;
        $this->type = $type;

        if ($rootElement) {
            if (
                !$rootElement instanceof Browser &&
                !$rootElement instanceof Element &&
                !$rootElement instanceof Elements
            ) {
                throw new Exception('Invalid rootElement type given');
            }

            $this->rootElement = $rootElement;
        }
        if ($remoteElement) {
            $this->remoteElement = $remoteElement;
        }
    }

    public function getBrowser(): Browser
    {
        return $this->browser;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        try {
            return !!$this->getDriverElement();
        } catch (NoSuchElementException $exc) {
            return false;
        }
    }

    /**
     * @return bool
     * @throws NoSuchElementException
     */
    public function isDisplayed(): bool
    {
        return $this->getDriverElement()->isDisplayed();
    }

    /**
     * @param Closure $callback
     * @return Element
     */
    public function tap(Closure $callback)
    {
        $callback($this->browser);

        return $this;
    }

    /**
     * @return Browser|Element|Elements|null
     */
    public function parent()
    {
        if (!isset($this->rootElement)) {
            return null;
        }

        return $this->rootElement;
    }

    /**
     * @param string $attributeName
     * @return string
     * @throws NoSuchElementException
     */
    public function getAttribute(string $attributeName): string
    {
        return $this->getDriverElement()->getAttribute($attributeName);
    }

    private function sleep(int $sleepSeconds = 0): void
    {
        if ($sleepSeconds > 0) {
            sleep($sleepSeconds);
        }
    }
}
