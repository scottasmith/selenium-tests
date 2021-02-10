<?php

namespace App\Domain\Sml\Responses;

class Disqualifications
{
    private array $disqualifications = [];

    public function add(Disqualification $disqualification): Disqualifications
    {
        $this->disqualifications[] = $disqualification;
        return $this;
    }
}
