<?php

namespace App\Domain\Sml;

use App\Domain\Selenium\Browser;
use App\Domain\Sml\Requests\RequestInterface;
use App\Domain\Sml\Responses\FailureResponse;
use App\Domain\Sml\Responses\ResponseInterface;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;

class Sml
{
    /**
     * @var Browser
     */
    private Browser $browser;

    /**
     * @param Browser $browser
     * @return Sml
     */
    public static function withBrowser(Browser $browser): Sml
    {
        $sml = new static();
        $sml->browser = $browser;
        return $sml;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws TimeoutException
     * @throws NoSuchElementException
     * @throws Exception
     */
    public function create(RequestInterface $request): ResponseInterface
    {
        $response = Login::withBrowser($this->browser)->fillForm($request);
        if ($response instanceof FailureResponse) {
            return $response;
        }

        $offences = Offences::withBrowser($this->browser)->getOffences();

        dd($offences);
    }
}
