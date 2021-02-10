<?php

namespace App\Domain\Selenium;

use App\Domain\Selenium\Traits\ElementDriver;
use Closure;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;

class Elements implements ElementInterface
{
    use ElementDriver;

    /**
     * @var Browser
     */
    private Browser $browser;

    /**
     * @var string
     */
    private string $selector;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var Browser|Element|Elements|null
     */
    private $rootElement;

    /**
     * Element constructor.
     * @param Browser $browser
     * @param string $selector
     * @param string $type
     * @param Browser|Element|null $rootElement
     * @throws Exception
     */
    public function __construct(Browser $browser, string $selector, string $type, $rootElement = null)
    {
        $this->browser = $browser;
        $this->selector = $selector;
        $this->type = $type;

        if ($rootElement) {
            if (
                !$rootElement instanceof Browser &&
                !$rootElement instanceof Element
            ) {
                throw new Exception('Invalid rootElement type given');
            }

            $this->rootElement = $rootElement;
        }

        if ($rootElement) {
            $this->rootElement = $rootElement;
        }
    }

    public function getBrowser(): Browser
    {
        return $this->browser;
    }

    /**
     * @return int
     * @throws NoSuchElementException
     */
    public function count(): int
    {
        $element = $this->getElements();
        return is_array($element) ? count($element) : 1;
    }

    /**
     * @param Closure $callback
     * @return Elements
     */
    public function tap(Closure $callback)
    {
        $callback($this->browser);

        return $this;
    }

    /**
     * @param Closure $callback
     * @return Elements
     * @throws Exception
     */
    public function each(Closure $callback)
    {
        /** @var RemoteWebElement $element */
        foreach ($this->getElements() as $element) {
            $callback(new Element($this->browser, $this->selector, $this->type, $this, $element));
        }

        return $this;
    }

    /**
     * @param Closure $callback
     * @return mixed
     * @throws Exception
     */
    public function map(Closure $callback)
    {
        $values = [];

        /** @var RemoteWebElement $element */
        foreach ($this->getElements() as $element) {
            $value = $callback(new Element($this->browser, $this->selector, $this->type, $this, $element));

            if ($value) {
                $values[] = $value;
            };
        }

        return $values;
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
     * @return RemoteWebElement|RemoteWebElement[]
     * @throws NoSuchElementException
     */
    private function getElements()
    {
        if (isset($this->rootElement) && $this->rootElement instanceof Element) {
            $rootRemoteElement = $this->rootElement->getDriverElement();
            return $rootRemoteElement->findElements($this->getWebDriverBy());
        }

        return $this->browser->getDriver()->findElements($this->getWebDriverBy());
    }
}
