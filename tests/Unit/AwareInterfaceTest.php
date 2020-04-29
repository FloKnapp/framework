<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\App;
use Webasics\Framework\Configuration\Config;

/**
 * Class AwareInterfaceTest
 * @package Webasics\Tests\Unit
 */
class AwareInterfaceTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldContainTheRouter()
    {
        $config = new Config([
            'routes' => RouterTest::ROUTE_COLLECTION
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/routerAware';

        $app = new App($config, App::ENVIRONMENT_TEST);

        $response = $app->run();

        // Request get's modified by TestListener and changed the request uri to "/test/dynamic"

        self::assertSame('index', $response);
    }

}