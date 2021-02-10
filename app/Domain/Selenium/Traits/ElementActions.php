<?php

namespace App\Domain\Selenium\Traits;

use App\Domain\Selenium\Element;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebElement;

/**
 * @method RemoteWebElement getDriverElement()
 */
trait ElementActions
{
    /**
     * @param string $text
     * @param int $sleepSeconds
     * @return Element
     * @throws NoSuchElementException
     */
    public function type(string $text, int $sleepSeconds = 0)
    {
        $this->sleep($sleepSeconds);
        $this->getDriverElement()->clear()->sendKeys($text);
        return $this;
    }

    /**
     * @param int $sleepSeconds
     * @return Element
     * @throws NoSuchElementException
     */
    public function click(int $sleepSeconds = 0)
    {
        $this->sleep($sleepSeconds);
        $this->getDriverElement()->click();
        return $this;
    }

    /**
     * @param int $sleepSeconds
     * @return Element
     * @throws NoSuchElementException
     */
    public function submit(int $sleepSeconds = 0)
    {
        $this->sleep($sleepSeconds);
        $this->getDriverElement()->submit();
        return $this;
    }

    /**
     * @return string
     * @throws NoSuchElementException
     */
    public function id(): string
    {
        return $this->getDriverElement()->getID();
    }

    /**
     * @return string
     * @throws NoSuchElementException
     */
    public function innerHTML(): string
    {
        return $this->getAttribute('innerHTML');
    }

    /**
     * @return string
     * @throws NoSuchElementException
     */
    public function innerText(): string
    {
        return $this->getAttribute('innerText');
    }
}
