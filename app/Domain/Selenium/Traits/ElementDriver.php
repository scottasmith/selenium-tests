<?php

namespace App\Domain\Selenium\Traits;

use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Element;
use App\Domain\Selenium\Elements;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;

/**
 * @property Browser $browser
 * @property Browser|Element|Elements $rootElement
 */
trait ElementDriver
{
    /**
     * @return RemoteWebElement
     * @throws NoSuchElementException
     */
    public function getDriverElement(): ?RemoteWebElement
    {
        if (isset($this->remoteElement)) {
            return $this->remoteElement;
        }

        /** @var RemoteWebDriver $driver */
        $driver = $this->browser->getDriver();

        if (isset($this->rootElement) && !$this->rootElement instanceof Browser) {
            $rootRemoteElement = $this->rootElement->getDriverElement();
            return $this->remoteElement = $rootRemoteElement->findElement($this->getWebDriverBy());
        }

        return $this->remoteElement = $driver->findElement($this->getWebDriverBy());
    }

    private function getWebDriverBy(): WebDriverBy
    {
        switch ($this->type) {
            case self::BY_CSS:
                return WebDriverBy::cssSelector($this->selector);
            case self::BY_CLASS:
                return WebDriverBy::className($this->selector);
            case self::BY_ID:
                return WebDriverBy::id($this->selector);
            case self::BY_LINK_TEXT:
                return WebDriverBy::linkText($this->selector);
            case self::BY_NAME:
                return WebDriverBy::name($this->selector);
            case self::BY_TAG_NAME:
                return WebDriverBy::tagName($this->selector);
            case self::BY_XPATH:
                return WebDriverBy::xpath($this->selector);
        }
    }
}
