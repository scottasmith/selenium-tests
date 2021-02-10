<?php

namespace App\Domain\Sml\Responses;

class Penalties
{
    /**
     * @var Penalty[]
     */
    private array $penalty = [];

    public function add(Penalty $penalty): Penalties
    {
        $this->penalty[] = $penalty;
        return $this;
    }
}
