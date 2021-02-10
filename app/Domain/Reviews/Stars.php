<?php

namespace App\Domain\Reviews;

class Stars
{
    /**
     * @var int
     */
    private int $max;

    /**
     * @var int
     */
    private int $given;

    /**
     * Stars constructor.
     * @param int $max
     * @param int $given
     */
    public function __construct(int $max, int $given)
    {
        $this->max = $max;
        $this->given = $given;
    }

    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * @return int
     */
    public function getGiven(): int
    {
        return $this->given;
    }
}
