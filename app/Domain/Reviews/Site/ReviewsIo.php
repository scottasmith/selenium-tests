<?php

namespace App\Domain\Reviews\Site;

use App\Domain\Proxy\ProxyMesh;
use App\Domain\Reviews\Review;
use App\Domain\Reviews\Stars;
use App\Domain\Reviews\UserDetector;
use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Element;
use App\Domain\Selenium\Options\BrowserOptions;
use App\Domain\Selenium\Options\Rect;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Throwable;

class ReviewsIo
{

    /**
     * @return Review[]
     * @throws Throwable
     */
    public function getReviews(): array
    {
        $options = (new BrowserOptions('http://172.29.48.1:4444', new Rect(1440, 1900)))
            ->setProxy(ProxyMesh::getProxy());

        $reviews = [];

        Browser::startChromeSession($options, function (Browser $browser) use (&$reviews) {
            $reviews = [];

            $browser
                ->visit("https://www.reviews.io/company-reviews/store/confused-onecalldirect")
                ->elementsByClass('Review')->each(function (Element $element) use (&$reviews) {
                    $reviews[] = $this->parseReview($element);
                });
        });

        return $reviews;
    }

    /**
     * @param Element $element
     * @return Review
     * @throws NoSuchElementException
     */
    private function parseReview(Element $element): Review
    {
        $author = $element->elementByClass('Review__author')->innerText();

        $reviewText = $element->elementByClass('Review__body')->innerText();
        $reviewText = str_replace(['“', '”', "\n"], '', $reviewText);

        $stars = $this->getStars($element);

        $review = (new Review($author, $reviewText))
            ->setStars($stars);

        foreach (UserDetector::guessNames($reviewText) as $name) {
            $review->addPossibleUser($name);
        }

        return $review;
    }

    /**
     * @param Element $element
     * @return Stars
     * @throws NoSuchElementException
     */
    private function getStars(Element $element): Stars
    {
        $maxStars = $element->elementsByCss('.stars__icon')->count();
        $given = $element->elementsByCss('.icon-full-star-01')->count();

        return new Stars($maxStars, $given);
    }
}
