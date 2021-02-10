<?php

namespace App\Domain\Sml;

use App\Domain\Selenium\Browser;
use App\Domain\Sml\Requests\CreateNoLicenceRequest;
use App\Domain\Sml\Requests\CreateWithLicenceRequest;
use App\Domain\Sml\Requests\RequestInterface;
use App\Domain\Sml\Responses\ResponseInterface;
use App\Domain\Sml\Responses\FailureResponse;
use App\Domain\Sml\Responses\CreateSuccessResponse;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;

class Login
{
    private Browser $browser;

    /**
     * @param Browser $browser
     * @return Login
     */
    public static function withBrowser(Browser $browser): Login
    {
        $login = new static();
        $login->browser = $browser;
        return $login;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface|null
     * @throws TimeoutException
     * @throws NoSuchElementException
     * @throws Exception
     */
    public function fillForm(RequestInterface $request): ?ResponseInterface
    {
        $failureResponse = null;
        $dln = null;

        if ($request instanceof CreateWithLicenceRequest) {
            $this->withLicence($request);

            if ($failureResponse = $this->getErrorSummary()) {
                return $failureResponse;
            }

            $dln = $request->getDln();
        } elseif ($request instanceof CreateNoLicenceRequest) {
            $this->withoutLicence($request);

            if ($failureResponse = $this->getErrorSummary()) {
                return $failureResponse;
            }

            $dln = $this->browser->element('dd.dln-field')->innerHTML();
        }

        $this->browser->element('[href="/driving-record/share"]')->click();
        $this->browser->waitForLocation('/driving-record/share', 20);

        $this->browser->element('[value="Get a code"]')->click();
        $this->browser->waitFor('.share-code');

        $checkCode = $this->browser->element('.share-code h2')->innerHTML();

        return new CreateSuccessResponse($dln, $checkCode);
    }

    /**
     * @param CreateWithLicenceRequest $request
     * @throws NoSuchElementException
     * @throws Exception
     */
    private function withLicence(CreateWithLicenceRequest $request)
    {
        $this->browser
            ->visit("https://www.viewdrivingrecord.service.gov.uk/driving-record/licence-number")
            ->element('#dln')->type($request->getDln(), 1)->parent()
            ->childByCss('#nino')->type($request->getNiNumber(), 1)->parent()
            ->childByCss('#postcode')->type($request->getPostcode(), 1)->parent()
            //
            ->childByCss('#dwpPermission')->click(2)->parent()
            ->childByCss('#submitDln')->click(4)->parent();
    }

    /**
     * @param CreateNoLicenceRequest $request
     * @throws NoSuchElementException
     * @throws Exception
     */
    private function withoutLicence(CreateNoLicenceRequest $request)
    {
        $this->browser
            ->visit("https://www.viewdrivingrecord.service.gov.uk/driving-record/personal-details")
            ->elementByCss('#nino')->type($request->getNiNumber())->parent()
            ->elementByCss('#forename')->type($request->getForename())->parent()
            ->elementByCss('#surname')->type($request->getSurname())->parent()
            ->elementByCss('#postcode')->type($request->getPostcode())->parent();

        if ($request->getGender() == $request::GENDER_F) {
            $this->browser->element('#female')->click();
        } else {
            $this->browser->element('#male')->click();
        }

        $dob = $request->getDob();
        $this->browser
            ->elementByCss('#dob_day')->type($dob->day)->parent()
            ->elementByCss('#dob_month')->type($dob->month)->parent()
            ->elementByCss('#dob_year')->type($dob->year)->parent()
            //
            ->elementByCss('#dwpPermission')->click(2)->parent()
            ->elementByCss('#submitDln')->click(6)->parent();
    }

    /**
     * @return FailureResponse|null
     * @throws NoSuchElementException
     * @throws Exception
     */
    private function getErrorSummary(): ?FailureResponse
    {
        $errorSummary = $this->browser->elementByCss('.error-summary');
        if ($errorSummary->exists() && $errorSummary->isDisplayed()) {
            $error = $errorSummary->elementByCss('p')->innerHTML();

            if ('Authentication was unsuccessful' == $error) {
                return new FailureResponse('Re-check the provided check code and driving license number');
            } elseif ('This check code has expired' == $error) {
                return new FailureResponse('The check code provided has expired');
            } elseif (strstr($error, 'technical problem')) {
                return new FailureResponse('There has been a technical problem. Please try again later');
            }

            return new FailureResponse($error);
        }

        return null;
    }
}
