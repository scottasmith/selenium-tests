<?php

namespace App\Console\Commands;

use App\Domain\Reviews\Site\ReviewsIo;
use Illuminate\Console\Command;
use Throwable;

class ReviewsIoCommand extends Command
{
    protected $signature = 'selenium:reviews:reviews-io';

    protected $description = 'Command description';

    /**
     * @param ReviewsIo $reviewsIo
     * @throws Throwable
     */
    public function handle(ReviewsIo $reviewsIo)
    {
        dd($reviewsIo->getReviews());
    }

}
