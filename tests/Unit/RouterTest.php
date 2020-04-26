<?php

namespace Webasics\Tests\Unit;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\RouteCollection;
use Webasics\Framework\Route\RouteItem;
use Webasics\Framework\Route\Router;
use Webasics\Tests\Fixtures\Controller\TestAwareController;
use Webasics\Tests\Fixtures\Controller\TestController;

class RouterTest extends TestCase
{

    private Router $router;

    const ROUTE_COLLECTION = [
        'test' => [
            'path'   => '/test',
            'class'  => TestController::class,
            'action' => 'index'
        ],
        'test_var' => [
            'path'   => '/test/:id',
            'class'  => TestController::class,
            'action' => 'dynamicPath'
        ],
        'router_aware_controller' => [
            'path'   => '/routerAware',
            'class'  => TestAwareController::class,
            'action' => 'routerAware'
        ],
        'invalid_response' => [
            'path' => '/test/invalid_response',
            'class' => TestController::class,
            'action' => 'invalidResponse'
        ],
        'invalid_method' => [
            'path' => '/test/invalid_method',
            'class' => TestController::class,
            'action' => 'invalidMethod'
        ]
    ];

    /**
     * Set up
     */
    public function setUp(): void
    {
        /** @var Dispatcher|ObjectProphecy $dispatcher */
        $dispatcher = $this->prophesize(Dispatcher::class);
        $dispatcher->forward(Argument::any())->willReturn($this->prophesize(ResponseInterface::class));

        $this->router = new Router($dispatcher->reveal(), self::ROUTE_COLLECTION);
    }

    /**
     * @test
     */
    public function itShouldReturnAGivenRoute()
    {
        self::assertInstanceOf(RouteItem::class, $this->router->getRoute('test'));
    }

    /**
     * @test
     *
     * @throws NotFoundException
     */
    public function itShouldMatchName()
    {
        self::assertSame(TestController::class, $this->router->getRoute('test')->getClass());
    }

    /**
     * @test
     *
     * @throws NotFoundException
     */
    public function itShouldNotMatchName()
    {
        self::expectException(NotFoundException::class);
        $this->router->getRoute('test_non_existent');
    }

    /**
     * @test
     */
    public function itShouldMatchPath()
    {
        $factory = new Psr17Factory();
        $request = $factory->createRequest('GET', '/test');
        self::assertInstanceOf(ResponseInterface::class, $this->router->dispatch($request));
    }

    /**
     * @test
     */
    public function itShouldMatchPathWithParameters()
    {
        $factory = new Psr17Factory();
        $request = $factory->createRequest('GET', '/test/123');
        self::assertInstanceOf(ResponseInterface::class, $this->router->dispatch($request));
    }

    /**
     * @test
     */
    public function itShouldNotMatchPath()
    {
        $factory = new Psr17Factory();
        $request = $factory->createRequest('GET', '/non_existent');

        self::expectException(NotFoundException::class);
        $this->router->dispatch($request);
    }

    /**
     * @test
     */
    public function itShouldReturnAnRouteItem()
    {
        self::assertInstanceOf(RouteItem::class, $this->router->getRoute('test'));
    }

    /**
     * @test
     */
    public function itShouldReturnAnRouteItemAsArray()
    {
        self::assertIsArray($this->router->getRoute('test')->toArray());
    }

}