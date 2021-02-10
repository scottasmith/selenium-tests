<?php

namespace App\Domain\Selenium\Traits;

use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Element;
use App\Domain\Selenium\Elements;
use BadMethodCallException;
use Exception;

/**
 * @method Element elementByCss(string $selector)
 * @method Element elementByClass(string $selector)
 * @method Element elementByLinkText(string $selector)
 * @method Element elementByName(string $selector)
 * @method Element elementByTagName(string $selector)
 * @method Element elementById(string $selector)
 * @method Element elementByXpath(string $selector)
 *
 * @method Elements elementsByCss(string $selector)
 * @method Elements elementsByClass(string $selector)
 * @method Elements elementsByLinkText(string $selector)
 * @method Elements elementsByName(string $selector)
 * @method Elements elementsByTagName(string $selector)
 * @method Elements elementsById(string $selector)
 * @method Elements elementsByXpath(string $selector)
 */
trait ElementSelectors
{

    /**
     * @param $method
     * @param $arguments
     * @return Element|Elements
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        $parentElement = null;
        if ($this instanceof Browser || $this instanceof Elements || $this instanceof Element) {
            $parentElement = $this;
        }

        $browser = null;
        if ($this instanceof Browser) {
            $browser = $this;
        } elseif ($this instanceof Elements || $this instanceof Element) {
            $browser = $this->getBrowser();
        }

        if ('elementBy' == substr($method, 0, 9)) {
            $methodSuffix = lcfirst(substr($method, 9));

            if (!in_array($methodSuffix, self::VALID_TYPES)) {
                throw new BadMethodCallException('Method ' . $method . ' does not exist');
            }
            if (1 !== count($arguments)) {
                throw new BadMethodCallException('Invalid arguments to ' . $method);
            }

            return new Element($browser, $arguments[0], $methodSuffix, $parentElement);
        } elseif ('elementsBy' == substr($method, 0, 10)) {
            $methodSuffix = lcfirst(substr($method, 10));

            if (!in_array($methodSuffix, self::VALID_TYPES)) {
                throw new BadMethodCallException('Method ' . $method . ' does not exist');
            }
            if (1 !== count($arguments)) {
                throw new BadMethodCallException('Invalid arguments to ' . $method);
            }

            return new Elements($browser, $arguments[0], $methodSuffix, $parentElement);
        }

        // Shouldn't get here
        throw new BadMethodCallException('Method ' . $method . ' does not exist');
    }
}
