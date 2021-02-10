<?php

namespace App\Domain\Selenium;

use App\Domain\Selenium\Options\BrowserOptions;
use App\Domain\Selenium\Traits\ElementSelectors;
use App\Domain\Selenium\Traits\WaitsForElements;
use Closure;
use Exception;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverDimension;
use Throwable;

class Browser implements ElementInterface
{
    use WaitsForElements;
    use ElementSelectors;

    private RemoteWebDriver $driver;

    private function __construct(RemoteWebDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param BrowserOptions $browserOptions
     * @param Closure $callback ($browser)
     * @throws Exception
     * @throws Throwable
     */
    public static function startChromeSession(BrowserOptions $browserOptions, Closure $callback): void
    {
        $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
        $capabilities = DesiredCapabilities::chrome()
            ->setCapability(ChromeOptions::CAPABILITY_W3C, $options);

        $browser = self::init($browserOptions, $capabilities);
        $driver = $browser->getDriver();

        if (extension_loaded('pcntl')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, function () use ($driver) {
                if ($driver) {
                    $driver->quit();
                }
                exit;
            });
        }

        register_shutdown_function(function () use ($driver) {
            if ($driver) {
                $driver->quit();
            }
        });

        $exception = null;
        try {
            $callback($browser);
        } catch (Throwable $exc) {
            $exception = $exc;
        }

        if ($driver) {
            $driver->close();
            $driver->quit();
        }

        if ($exception) {
            throw $exception;
        }
    }

    /**
     * @param BrowserOptions $browserOptions
     * @param DesiredCapabilities $capabilities
     * @return Browser
     * @throws Exception
     */
    private static function init(BrowserOptions $browserOptions, DesiredCapabilities $capabilities): Browser
    {
        if ($proxy = $browserOptions->getProxy()) {
            $capabilities->setCapability(WebDriverCapabilityType::PROXY, [
                'proxyType' => 'manual',
                'httpProxy' => $proxy,
            ]);
        }

        $driver = RemoteWebDriver::create($browserOptions->getHost(), $capabilities);

        $browser = new static($driver);
        if ($rect = $browserOptions->getWindowRect()) {
            $browser->resize($rect->getWidth(), $rect->getHeight());
        }

        return $browser;
    }

    public function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

    public function resize($width, $height)
    {
        $this->driver->manage()->window()->setSize(
            new WebDriverDimension($width, $height)
        );

        return $this;
    }

    /**
     * @param string $url
     * @return Browser
     */
    public function visit(string $url): Browser
    {
        $this->driver->get($url);

        return $this;
    }

    /**
     * Pause for the given amount of milliseconds.
     *
     * @param int $milliseconds
     * @return Browser
     */
    public function pause($milliseconds): Browser
    {
        usleep($milliseconds * 1000);

        return $this;
    }

    /**
     * Execute a Closure with a scoped browser instance.
     *
     * @param string $selector
     * @param string $type
     * @param Closure $callback
     * @return Browser
     * @throws Exception
     */
    public function elementWith($selector, string $type, Closure $callback)
    {
        $element = new Element($this, $selector, $type);
        call_user_func($callback, $element);

        return $this;
    }

    /**
     * Refresh the page.
     *
     * @return Browser
     */
    public function refresh()
    {
        $this->driver->navigate()->refresh();

        return $this;
    }

    /**
     * Navigate to the previous page.
     *
     * @return Browser
     */
    public function back()
    {
        $this->driver->navigate()->back();

        return $this;
    }

    /**
     * Dump the content from the last response.
     *
     * @return void
     */
    public function dump()
    {
        dump($this->driver->getPageSource());
    }

    /**
     * Pause execution of test and open Laravel Tinker (PsySH) REPL.
     *
     * @return Browser
     */
    public function tinker()
    {
        \Psy\debug([
            'browser' => $this,
            'driver'  => $this->driver,
        ], $this);

        return $this;
    }
}
