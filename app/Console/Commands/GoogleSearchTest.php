<?php

namespace App\Console\Commands;

use App\Domain\Proxy\ProxyMesh;
use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Options\BrowserOptions;
use App\Domain\Selenium\Options\Rect;
use App\Domain\Sml\Requests\CreateWithLicenceRequest;
use App\Domain\Sml\Sml;
use Exception;
use Illuminate\Console\Command;

class GoogleSearchTest extends Command
{
    protected $signature = 'selenium:google';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $options = (new BrowserOptions('http://172.29.48.1:4444', new Rect(1440, 1900)))
            ->setProxy(ProxyMesh::getProxy());

        Browser::startChromeSession($options, function (Browser $browser) {
            try {
                $browser
                    ->visit("https://google.com")
                    ->elementByName('q')->type('how do i')->submit();
            } catch (\Throwable $exc) {
                dump($exc);
            }

            sleep(100);
        });

    }
}
