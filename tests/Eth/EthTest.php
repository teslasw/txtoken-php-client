<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Txtoken\TxtokenApiClient;
use Txtoken\Tests\Credentials;

// use Txtoken\Tests\TestCase;

// class EthTest extends TestCase
// {


//     // public function testTaxableCountriesListRetrieved()
//     // {
//     //     $rates = new PrintfulTaxRates($this->api);

//     //     $taxableCountriesList = $rates->getTaxCountries();
//     //     self::assertInstanceOf(CountryItem::class, reset($taxableCountriesList));
//     // }




// }

final class EthTest extends TestCase
{

    /** @var TxtokenApiClient */
    protected $api;

    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(Credentials::class)) {
            throw new \Exception('Txtoken test credentials are not set. Copy "tests/Credentials.php.dist" to "tests/Credentials.php and enter the API key');
        }

        $this->api = new TxtokenApiClient(Credentials::$apiId, Credentials::$apiSecret);

        // Override API URL if is set
        if (Credentials::$apiUrlOverride) {
            $this->api->url = Credentials::$apiUrlOverride;
        }

        
    }






    public function testX()
    {
        $rates = $this->api;

        $this->prontoPrint($rates);

        //$this->assertEquals('y', 'y');
    }


    protected function prontoPrint($whatever = 'I am printed!')
{
    // if output buffer has not started yet
    if (ob_get_level() == 0) {
        // current buffer existence
        $hasBuffer = false;
        // start the buffer
        ob_start();
    } else {
        // current buffer existence
        $hasBuffer = true;
    }

    // echo to output
    echo $whatever;

    // flush current buffer to output stream
    ob_flush();
    flush();
    ob_end_flush();

    // if there were a buffer before this method was called
    //      in my version of PHPUNIT it has its own buffer running
    if ($hasBuffer) {
        // start the output buffer again
        ob_start();
    }
}
}
