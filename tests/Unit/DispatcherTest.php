<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\MethodNotFoundException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\RouteCollection;
use Webasics\Framework\Route\RouteItem;
use Webasics\Tests\Fixtures\Controller\TestController;

/**
 * Class DispatcherTest
 * @package Webasics\Tests\Unit
 */
class DispatcherTest extends TestCase
{

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldReturnValidResponse()
    {
        $dispatcher      = new Dispatcher();
        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $result = $dispatcher->dispatch($routeCollection->get('test'));

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldThrowExceptionForInvalidResponse()
    {
        self::expectException(InvalidResponseException::class);

        $dispatcher      = new Dispatcher();
        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $dispatcher->dispatch($routeCollection->get('invalid_response'));
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldThrowExceptionMissingMethod()
    {
        self::expectException(NotFoundException::class);

        $dispatcher      = new Dispatcher();
        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $dispatcher->dispatch($routeCollection->get('invalid_method'));
    }

}