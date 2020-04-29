<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\App;
use Webasics\Framework\Configuration\Config;
use Webasics\Framework\Route\Exception\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Tests\Fixtures\Event\RewriteUrlListener;

/**
 * Class AppTest
 * @package Webasics\Tests\Unit
 */
class AppTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldReturnResponse()
    {
        $config = new Config([
            'routes' => RouterTest::ROUTE_COLLECTION
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $app = new App($config, App::ENVIRONMENT_TEST);

        $response = $app->run();

        self::assertSame('test', $response);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldRegisterEventListener()
    {
        $config = new Config([
            'routes'        => RouterTest::ROUTE_COLLECTION,
            'eventListener' => [
                RewriteUrlListener::class
            ]
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test/dynamic';

        $app2 = new App($config, App::ENVIRONMENT_TEST);

        $response2 = $app2->run();

        // Request get's modified by TestListener and changed the request uri to "/test/dynamic"

        self::assertSame('dynamic', $response2);
    }

}