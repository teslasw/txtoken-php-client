<?php

namespace Txtoken\Tests;

use Txtoken\TxtokenApiClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var TxtokenApiClient */
    protected $api;

    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(Credentials::class)) {
            throw new \Exception('Txtoken test credentials are not set. Copy "tests/Credentials.php.dist" to "tests/Credentials.php and enter the API key');
        }

        $this->api = new TxtokenApiClient(Credentials::$apiKey);

        // Override API URL if is set
        if (Credentials::$apiUrlOverride) {
            $this->api->url = Credentials::$apiUrlOverride;
        }

        // code goes here


        //$this->assertEquals('y', 'y');
    }


}