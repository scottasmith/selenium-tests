<?php

namespace App\Domain\Selenium\Options;

class Rect
{
    /**
     * @var int
     */
    private int $width;

    /**
     * @var int
     */
    private int $height;

    /**
     * Rect constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
