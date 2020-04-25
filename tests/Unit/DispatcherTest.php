<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\MethodNotFoundException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Initializer;
use Webasics\Framework\Route\RouteCollection;

/**
 * Class DispatcherTest
 * @package Webasics\Tests\Unit
 */
class DispatcherTest extends TestCase
{

    /** @var Dispatcher */
    private Dispatcher $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new Dispatcher(new Initializer($this->prophesize(Container::class)->reveal()));
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldReturnValidResponse()
    {
        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $result = $this->dispatcher->dispatch($routeCollection->get('test'));

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

        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $this->dispatcher->dispatch($routeCollection->get('invalid_response'));
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

        $routeCollection = new RouteCollection(RouterTest::ROUTE_COLLECTION);

        $this->dispatcher->dispatch($routeCollection->get('invalid_method'));
    }

}