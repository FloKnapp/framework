<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\EventDispatcher\Observer;
use Webasics\Framework\Route\RouteItem;
use Webasics\Framework\Route\Exception\InitializerException;
use Webasics\Framework\Route\Exception\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Initializer;
use Webasics\Tests\Fixtures\Controller\TestController;

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
        $observer  = $this->prophesize(Observer::class);
        $container = $this->prophesize(Container::class);
        $container->get(Argument::is(Observer::class))->willReturn($observer->reveal());

        $initializer = $this->prophesize(Initializer::class);
        $initializer->willBeConstructedWith([$container->reveal()]);

        $controller = $this->prophesize(TestController::class);
        $controller->index()->willReturn($this->prophesize(ResponseInterface::class));
        $controller->invalidResponse()->willReturn(null);
        $controller->dynamicPath()->willReturn($this->prophesize(ResponseInterface::class));

        $initializer->loadClass(Argument::is(TestController::class))->willReturn($controller->reveal());
        $this->dispatcher = new Dispatcher($initializer->reveal());
        $this->dispatcher->setObserver($observer->reveal());
    }

    /**
     * @param string $name
     * @param array  $data
     * @return RouteItem
     */
    private function createRouteEntity(string $name, array $data)
    {
        return new RouteItem($name, $data['path'], $data['class'], $data['action'], $data['parameters'] ?? []);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     * @throws InitializerException
     */
    public function itShouldReturnValidResponse()
    {
        $result = $this->dispatcher->forward($this->createRouteEntity('test', RouterTest::ROUTE_COLLECTION['test']));

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     * @throws InitializerException
     */
    public function itShouldThrowExceptionForInvalidResponse()
    {
        self::expectException(InvalidResponseException::class);

        $this->dispatcher->forward($this->createRouteEntity('invalid_response', RouterTest::ROUTE_COLLECTION['invalid_response']));
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     * @throws InitializerException
     */
    public function itShouldThrowExceptionMissingMethod()
    {
        self::expectException(NotFoundException::class);

        $this->dispatcher->forward($this->createRouteEntity('invalid_method', RouterTest::ROUTE_COLLECTION['invalid_method']));
    }

}