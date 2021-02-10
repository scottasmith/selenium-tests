<?php

namespace App\Console\Commands;

use App\Domain\Proxy\ProxyMesh;
use App\Domain\Selenium\Browser;
use App\Domain\Selenium\Options\BrowserOptions;
use App\Domain\Selenium\Options\Rect;
use App\Domain\Sml\Requests\CreateWithLicenceRequest;
use App\Domain\Sml\Sml;
use Illuminate\Console\Command;

class SmlTest extends Command
{
    protected $signature = 'selenium:sml';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $options = (new BrowserOptions('http://172.29.48.1:4444', new Rect(1440, 1900)))
            ->setProxy(ProxyMesh::getProxy());

        Browser::startChromeSession($options, function ($browser) {
            $sml = Sml::withBrowser($browser);

            $withRequest = new CreateWithLicenceRequest('SMITH811252SA9BT', 'JT422397B', 'DN85NY');

            $response = $sml->create($withRequest);
            dump($response);
        });

//
//        $withRequest = new CreateWithLicenceRequest('SMITH811252SA9BT', 'JT422397B', 'DN85NY');
//
////        $response = $sml->create($withRequest);
////        dump($response);
//
//
//        $withoutRequest = new CreateNoLicenceRequest(
//            'JT422397B',
//            'Scott Adrian',
//            'Smith',
//            'DN85NY',
//            CreateNoLicenceRequest::GENDER_M,
//            Carbon::createFromDate('1982-11-25')
//        );
//
//        $response = $sml->create($withoutRequest);
//        dump($response);
//
//        sleep(20);
    }


}
