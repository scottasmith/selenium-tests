<?php

namespace App\Domain\Sml;

use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Element;
use App\Domain\Sml\Responses\Disqualification;
use App\Domain\Sml\Responses\Disqualifications;
use App\Domain\Sml\Responses\Penalties;
use App\Domain\Sml\Responses\Penalty;
use Exception;
use Facebook\WebDriver\Exception\NoSuchElementException;

class Offences
{
    /**
     * @var Browser
     */
    private Browser $browser;

    /**
     * @param Browser $browser
     * @return Offences
     */
    public static function withBrowser(Browser $browser): Offences
    {
        $offences = new static();
        $offences->browser = $browser;
        return $offences;
    }

    /**
     * @return Penalties|array
     * @throws Exception
     */
    public function getOffences()
    {
        $largeHeader = $this->browser->elementByCss('.heading-large');
        $penalties = [];
        $disqualifications = [];

        // TODO: NON of these offence mappings look right BUT copied from Adam's code!

        if ($largeHeader->exists()) {
            $penaltyPointsEl = $this->browser->elementByCss('#penaltyPoints');
            if ($penaltyPointsEl->exists()) {
                $penalties = $this->extractPenalties($penaltyPointsEl);
            }

            $disqualificationsEl = $this->browser->elementByCss('#disqualifications');
            if ($disqualificationsEl->exists()) {
                $disqualifications = $this->extractDisqualifications($disqualificationsEl);
            }
        }

        return $penalties;
    }

    /**
     * @param Element $penaltyPoints
     * @return Penalties
     * @throws Exception
     */
    private function extractPenalties(Element $penaltyPoints): Penalties
    {
        $list = new Penalties();

        $penaltyPoints->elementsByTagName('li')->each(function (Element $element) use ($list) {
            $detailEl = $element->elementByCss('.accordion-group')->elementByCss('.detail-col');
            $categoryEl = $detailEl->elementByCss('category');
            if (!$categoryEl) {
                return null;
            }

            $accordianContentEl = $element->elementByCss('.accordion-content');
            $offenceDatesEl = $accordianContentEl->elementByCss('.offence-dates');
            $offenceDetailEl = $accordianContentEl->elementByCss('.offence-details');

            $penalty = (new Penalty())
                ->setCategory($categoryEl->innerHTML())
                ->setPoints($this->getExplodedVal($detailEl->elementByCss('.points'), ' ', 2))
                ->setDate($this->getExplodedVal($detailEl->elementByCss('.offence-date'), ': ', 1))
                ->setOffenseDate($this->getPenaltyValue($offenceDatesEl, '> ', 1))
                ->setOffenceExpiryDate($this->getPenaltyValue($offenceDatesEl, '> ', 1))
                ->setOffenceRemovalDate($this->getPenaltyValue($offenceDatesEl, '> ', 2))
                ->setOffenceDescription($this->getPenaltyValue($offenceDetailEl, '> ', 3))
                ->setOffencePoints($this->getPenaltyValue($offenceDetailEl, '> ', 1))
                ->setOffenceFine($this->getPenaltyValue($offenceDetailEl, '£', 2))
                ->setPenaltyOffice($element->elementByCss('li:nth-child(3) p')->innerHTML());

            $list->add($penalty);
        });

        return $list;
    }

    /**
     * @param Element $disqualifications
     * @return Disqualifications
     * @throws Exception
     */
    private function extractDisqualifications(Element $disqualifications): Disqualifications
    {
        $list = new Disqualifications();

        $disqualifications->elementsByTagName('li')->each(function (Element $element) use ($list) {
            $accordianEl = $element->elementByCss('.accordion-group');
            $detailEl = $accordianEl->elementByCss('.detail-col');
            $categoryEl = $detailEl->elementByCss('category');
            if (!$categoryEl) {
                return null;
            }

            $accordianContentEl = $element->elementByCss('.accordion-content');

            $disqualification = (new Disqualification())
                ->setCategory($categoryEl->innerHTML())
                ->setPoints($this->getExplodedVal($detailEl->elementByCss('.period'), ' ', 1))
                ->setDate($this->getExplodedVal($detailEl->elementByCss('.start'), ': ', 0))
                ->setOffenseDate($this->getDisqualifiedValue($accordianContentEl, '> ', 1))
                ->setOffenceExpiryDate($this->getDisqualifiedValue($accordianContentEl, '> ', 2))
                ->setOffenceRemovalDate($this->getDisqualifiedValue($accordianContentEl, '> ', 4))
                ->setOffenceDescription($this->getDisqualifiedValue($accordianContentEl, '> ', 1))
                ->setOffencePoints($this->getDisqualifiedValue($accordianContentEl, '> ', 3))
                ->setOffenceFine($this->getDisqualifiedValue($accordianContentEl, '£', 2))
                ->setPenaltyOffice($accordianEl->elementByCss('.offence-court p')->innerHTML());

            $list->add($disqualification);
        });

        return $list;
    }

    /**
     * @param Element $element
     * @param string $delimiter
     * @param int $position
     * @return string|null
     * @throws NoSuchElementException
     */
    private function getExplodedVal(Element $element, string $delimiter = ' ', int $position = 0): ?string
    {
        $value = $element->innerHTML();
        if (!$value) {
            return null;
        }

        return (string)explode($delimiter, $value)[$position];
    }

    /**
     * @param Element $element
     * @param string $delimiter
     * @param int $offset
     * @return string:null
     * @throws NoSuchElementException
     * @throws Exception
     */
    private function getPenaltyValue(Element $element, string $delimiter = ' ', int $offset = 0): ?string
    {
        $value = $element->elementByCss('ul li:nth-child(' . $offset . ')')->innerHTML();
        if (!$value) {
            return null;
        }

        return explode($delimiter, $value)[1];
    }

    /**
     * @param Element $element
     * @param string $delimiter
     * @param int $offset
     * @return string|null
     * @throws NoSuchElementException
     */
    private function getDisqualifiedValue(Element $element, string $delimiter = '', int $offset = 0): ?string
    {
        $value = $element->elementByCss('ul:nth-child(1) ul li:nth-child(' . $offset . ')')->innerHTML();
        if (!$value) {
            return null;
        }

        return explode($delimiter, $value)[1];
    }

}
