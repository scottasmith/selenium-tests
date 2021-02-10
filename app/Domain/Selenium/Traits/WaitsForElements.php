<?php

namespace App\Domain\Selenium\Traits;

use App\Domain\Selenium\Element;
use Carbon\Carbon;
use Closure;
use Exception;
use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Support\Str;

trait WaitsForElements
{
    /**
     * Execute the given callback in a scoped browser once the selector is available.
     *
     * @param string $selector
     * @param Closure $callback
     * @param int $seconds
     * @return WaitsForElements
     * @throws TimeOutException
     */
    public function whenAvailable($selector, Closure $callback, $seconds = null)
    {
        return $this->waitFor($selector, $seconds)->elementWith($selector, $callback);
    }

    /**
     * Wait for the given selector to be visible.
     *
     * @param string $selector
     * @param int $seconds
     * @return WaitsForElements
     * @throws TimeOutException
     */
    public function waitFor(string $selector, int $seconds = null)
    {
        $message = $this->formatTimeOutMessage('Waited %s seconds for selector', $selector);

        return $this->waitUsing($seconds, 100, function () use ($selector) {
            /** @var Element $element */
            $element = $this->element($selector);
            return $element->isDisplayed();
        }, $message);
    }

    /**
     * Wait for the given location.
     *
     * @param  string  $path
     * @param  int  $seconds
     * @return WaitsForElements
     * @throws TimeOutException
     */
    public function waitForLocation($path, $seconds = null)
    {
        $message = $this->formatTimeOutMessage('Waited %s seconds for location', $path);

        return $this->waitUntil("window.location.pathname == '{$path}'", $seconds, $message);
    }

    /**
     * Wait for the given callback to be true.
     *
     * @param int $seconds
     * @param int $interval
     * @param Closure $callback
     * @param string|null $message
     * @return $this
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function waitUsing($seconds, $interval, Closure $callback, $message = null)
    {
        $this->pause($interval);

        $started = Carbon::now();

        while (true) {
            try {
                if ($callback()) {
                    break;
                }
            } catch (Exception $e) {
                //
            }

            if ($started->lt(Carbon::now()->subSeconds($seconds))) {
                throw new TimeOutException($message
                    ? sprintf($message, $seconds)
                    : "Waited {$seconds} seconds for callback."
                );
            }

            $this->pause($interval);
        }

        return $this;
    }

    /**
     * Wait until the given script returns true.
     *
     * @param  string  $script
     * @param  int  $seconds
     * @param  string  $message
     * @return WaitsForElements
     * @throws TimeOutException
     */
    public function waitUntil($script, $seconds = null, $message = null)
    {
        if (! Str::startsWith($script, 'return ')) {
            $script = 'return '.$script;
        }

        if (! Str::endsWith($script, ';')) {
            $script = $script.';';
        }

        return $this->waitUsing($seconds, 100, function () use ($script) {
            return $this->getDriver()->executeScript($script);
        }, $message);
    }

    /**
     * Prepare custom TimeOutException message for sprintf().
     *
     * @param string $message
     * @param string $expected
     * @return string
     */
    protected function formatTimeOutMessage($message, $expected)
    {
        return $message . ' [' . str_replace('%', '%%', $expected) . '].';
    }
}
