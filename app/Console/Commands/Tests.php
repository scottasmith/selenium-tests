<?php

namespace App\Console\Commands;

use App\Domain\Reviews\UserDetector;
use Illuminate\Console\Command;

class Tests extends Command
{
    protected $signature = 'tests';

    protected $description = 'Command description';

    public function handle()
    {
        $text = 'I found your live chat was very easy and straight forward, my advisor r02 was very helpful, with my insurance amendment';

        $names = UserDetector::guessNames($text);

        dd($names);
    }
}
