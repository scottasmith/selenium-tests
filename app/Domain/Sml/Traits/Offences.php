<?php

namespace App\Domain\Sml\Traits;

trait Offences
{
    private function scrapeOffences()
    {
        $this->browser->element('.heading-large');
    }
}
